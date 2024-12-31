<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Exception;
use Illuminate\Support\Carbon;
use Omnipay\Alipay\AopPageGateway;
use Omnipay\Alipay\Responses\AbstractResponse;
use Omnipay\Alipay\Responses\AopCompletePurchaseResponse;
use Omnipay\Alipay\Responses\AopTradeAppPayResponse;
use Omnipay\Alipay\Responses\AopTradeCreateResponse;
use Omnipay\Alipay\Responses\AopTradePagePayResponse;
use Omnipay\Alipay\Responses\AopTradePreCreateResponse;
use Omnipay\Alipay\Responses\AopTradeWapPayResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Data\ChannelRefundData;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Data\PaymentTrigger;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelPurchaseResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\GatewayDriveInterface;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\PaymentTriggerTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RuntimeException;
use Throwable;

/**
 * 渠道适配器
 */
class AlipayGatewayDrive implements GatewayDriveInterface
{


    /**
     * @var GatewayInterface
     */
    protected GatewayInterface    $gateway;
    protected ChannelApp          $channelApp;
    protected ?ChannelProduct     $channelProduct;
    protected ?PaymentChannelData $paymentChannelData;

    public function gateway(PaymentChannelData $paymentChannelData) : static
    {
        $this->paymentChannelData = $paymentChannelData;
        $this->channelProduct     = $paymentChannelData->channelProduct;
        $this->channelApp         = $paymentChannelData->channelApp;
        $gatewayName              = $this->channelProduct->gateway ?? 'Alipay_AopApp';
        $this->gateway            = $this->initChannelApp(Omnipay::create($gatewayName), $this->channelApp);

        return $this;

    }


    protected function initChannelApp(GatewayInterface $gateway, ChannelApp $channelApp) : GatewayInterface
    {
        /**
         * @var $gateway AopPageGateway
         */

        $gateway->setSignType($channelApp->sign_type);
        $gateway->setAppId($channelApp->channel_app_id);
        $gateway->setPrivateKey($channelApp->channel_app_private_key);
        $gateway->setNotifyUrl(PaymentUrl::notifyUrl($channelApp));


        switch ($channelApp->sign_method) {
            case SignMethodEnum::Secret:
                if (blank($channelApp->channel_public_key)) {
                    throw new RuntimeException('支付宝证书公钥不存在');
                }
                $gateway->setAlipayPublicKey($channelApp->channel_public_key);
                break;
            case SignMethodEnum::Cert:
                if (blank($channelApp->channel_root_cert)) {
                    throw new RuntimeException('支付宝证书根证书不存在');
                }
                if (blank($channelApp->channel_public_key_cert)) {
                    throw new RuntimeException('支付宝证书公钥不存在');
                }

                if (blank($channelApp->channel_app_public_key_cert)) {
                    throw new RuntimeException('支付宝证书应用公钥不存在');
                }
                $gateway->setAlipayRootCert($channelApp->channel_root_cert);
                $gateway->setAlipayPublicCert($channelApp->channel_public_key_cert);
                $gateway->setAppCert($channelApp->channel_app_public_key_cert);
                $gateway->setCheckAlipayPublicCert(true);
                break;
            default:
                throw new RuntimeException('不支持的签名方式');
        }
        // 内容加密
        if (filled($channelApp->encrypt_key)) {
            $gateway->setEncryptKey($channelApp->encrypt_key);
        }


        return $gateway;
    }

    /**
     * @param Trade $trade
     * @param Environment $environment
     * @return ChannelPurchaseResult
     * @throws Throwable
     */
    public function purchase(Trade $trade, Environment $environment) : ChannelPurchaseResult
    {


        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        if (method_exists($gateway, 'setReturnUrl')) {
            $gateway->setReturnUrl(PaymentUrl::returnUrl($trade));
        }

        // TODO 更多参数
        $request = $gateway->purchase(
            [ 'biz_content' => [

                'out_trade_no'      => $trade->trade_no,
                'total_amount'      => $trade->amount->format(),
                'subject'           => $trade->subject,
                'product_code'      => $this->channelProduct->code,
                'merchant_order_no' => $trade->merchant_order_no,
            ] ]
        );


        try {

            /**
             * @var $response AbstractResponse
             */
            $response = $request->send();

            $result = new ChannelPurchaseResult;

            $result->setSuccessFul(false);

            $result->setMessage($response->getMessage());
            $result->setCode($response->getCode());
            $result->setData($response->getData());

            if ($response->isSuccessful()) {
                $result->setSuccessFul(true);
                $paymentTrigger = new PaymentTrigger();

                if ($response instanceof AopTradePagePayResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::REDIRECT;
                    $paymentTrigger->content = $response->getRedirectUrl();

                }
                if ($response instanceof AopTradeWapPayResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::REDIRECT;
                    $paymentTrigger->content = $response->getRedirectUrl();
                }
                if ($response instanceof AopTradeAppPayResponse) {
                    $paymentTrigger->type = PaymentTriggerTypeEnum::IN_APP;

                    $paymentTrigger->content = $response->getOrderString();
                }
                if ($response instanceof AopTradePreCreateResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::QR_CODE;
                    $paymentTrigger->content = $response->getQrCode();
                }
                if ($response instanceof AopTradeCreateResponse) {
                    $paymentTrigger->type    = PaymentTriggerTypeEnum::APPLET;
                    $paymentTrigger->content = $response->getTradeNo();
                }
                $result->paymentTrigger = $paymentTrigger;
            }
            return $result;
        } catch (Throwable $throwable) {
            report($throwable);
            throw $throwable;

        }

    }

    /**
     * 完成支付
     * @param array $parameters
     * @return ChannelTradeData
     * @throws InvalidRequestException
     */
    public function completePurchase(array $parameters = []) : ChannelTradeData
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;


        $request = $gateway->completePurchase()->setParams($parameters);

        try {
            /**
             * @var $response AopCompletePurchaseResponse
             */
            $response = $request->send();


            if ($response->isPaid()) {
                $data = $response->getData();


                // 调用查询接口
                $queryResponse = $gateway->query([
                                                     'biz_content' => [
                                                         'trade_no'      => $data['trade_no'],
                                                         'query_options' => [
                                                             'buyer_user_type',
                                                             'buyer_open_id'
                                                         ],
                                                     ] ])->send();


                if ($queryResponse->isSuccessful()) {
                    // 合并参数
                    $data = array_merge($data, $queryResponse->getAlipayResponse());
                }


                $channelTradeData                     = new  ChannelTradeData;
                $channelTradeData->originalParameters = $data;
                $channelTradeData->channelCode        = $this->channelApp->channel_code;
                $channelTradeData->channelMerchantId  = $this->channelApp->channel_merchant_id;
                $channelTradeData->channelAppId       = (string)$data['app_id'];
                $channelTradeData->tradeNo            = (string)$data['out_trade_no'];
                $channelTradeData->channelTradeNo     = (string)$data['trade_no'];
                $channelTradeData->amount             = new Money(bcmul($data['total_amount'], 100, 0));
                $channelTradeData->paymentAmount      = new Money(bcmul($data['total_amount'], 100, 0));
                $channelTradeData->status             = TradeStatusEnum::SUCCESS;
                $channelTradeData->payer              = Payer::from([
                                                                        'type'    => $data['buyer_user_type'] ?? null,
                                                                        'userId'  => $data['buyer_id'] ?? null,
                                                                        'account' => $data['buyer_logon_id'] ?? null,
                                                                        'openId'  => $data['buyer_open_id'] ?? null,
                                                                        'name'    => null,
                                                                    ]);
                $channelTradeData->paidTime           = Carbon::make($data['gmt_payment']);

                return $channelTradeData;
            }
        } catch (Exception $e) {
            throw $e;
            /**
             * Payment is not successful
             */
            //die('fail'); //The notify response
        }
    }

    public function refund(Refund $refund) : ChannelRefundResult
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        $request = $gateway->refund([
                                        'biz_content' => [
                                            'refund_amount'       => $refund->refundAmount->format(),
                                            'out_request_no'      => $refund->refund_no,
                                            'out_trade_no'        => $refund->trade_no,
                                            'trade_no'            => $refund->channel_trade_no,
                                            'refund_reason'       => $refund->refund_reason,
                                            'refund_goods_detail' => [],

                                        ]
                                    ]);

        try {
            $response = $request->send();
            $data     = $response->getAlipayResponse();

            $result = new ChannelRefundResult;
            $result->setMessage($data['sub_msg'] ?? $response->getMessage());
            $result->setCode($response->getCode());
            $result->setSuccessFul(false);
            $result->setData($response->getData());
            // 请求成功
            if ($response->isSuccessful()) {
                $result->setSuccessFul(true);
            }
            return $result;
        } catch (Throwable $throwable) {
            throw  $throwable;
        }

    }

    public function refundQuery(Refund $refund) : ChannelRefundData
    {
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        $request = $gateway->refundQuery([
                                             'biz_content' => [

                                                 'out_request_no' => $refund->refund_no,
                                                 'out_trade_no'   => $refund->trade_no,
                                                 'trade_no'       => $refund->channel_trade_no,
                                                 'query_options'  => [
                                                     'refund_royaltys',
                                                     'gmt_refund_pay',
                                                     'refund_detail_item_list',
                                                     'send_back_fee',
                                                     'deposit_back_info',
                                                     'refund_voucher_detail_list',
                                                     'pre_auth_cancel_fee',
                                                     'refund_hyb_amount',
                                                     'refund_charge_info_list',
                                                 ],
                                             ]
                                         ]);

        try {

            $response = $request->send();

            if (!$response->isSuccessful()) {
                throw new RuntimeException('退款失败');
            }
            $data                        = $response->getAlipayResponse();
            $channelRefundData           = new ChannelRefundData();
            $channelRefundData->status   = RefundStatusEnum::PROCESSING;
            $channelRefundData->tradeNo  = $data['out_trade_no'];
            $channelRefundData->refundNo = $data['out_request_no'];
            if ((($data['refund_status'] ?? '') === 'REFUND_SUCCESS')) {
                $channelRefundData->refundTime   = Carbon::make($data['gmt_refund_pay']);
                $channelRefundData->refundAmount = new Money(bcmul($data['refund_amount'], 100, 0));

                return $channelRefundData;
            }
            return $channelRefundData;
        } catch (Throwable $throwable) {
            throw $throwable;
        }
    }


    public function notifyResponse() : NotifyResponseInterface
    {
        return new   AlipayNotifyResponse();
    }


}
