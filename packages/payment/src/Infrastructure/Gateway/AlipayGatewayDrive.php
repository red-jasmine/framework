<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Dflydev\DotAccessData\Data;
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
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\GatewayDriveInterface;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;

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

        $gateway->setSignType('RSA2');
        $gateway->setAppId($channelApp->channel_app_id);
        $gateway->setPrivateKey($channelApp->channel_app_private_key);
        $gateway->setNotifyUrl(PaymentUrl::notifyUrl($channelApp));

        if ($channelApp->sign_method === SignMethodEnum::Secret) {
            $gateway->setAlipayPublicKey($channelApp->channel_public_key);
        }

        if ($channelApp->sign_method === SignMethodEnum::Cert) {

            $gateway->setAlipayRootCert($channelApp->channel_root_cert);
            $gateway->setAlipayPublicCert($channelApp->channel_public_key_cert);
            $gateway->setAppCert($channelApp->channel_app_public_key_cert);
            $gateway->setCheckAlipayPublicCert(true);
        }

        // 内容加密
        $gateway->setEncryptKey($channelApp->encrypt_key);

        return $gateway;
    }

    public function purchase(Trade $trade, Environment $environment) : ChannelResult
    {


        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        if (method_exists($gateway, 'setReturnUrl')) {
            $gateway->setReturnUrl(PaymentUrl::returnUrl($trade));
        }


        $request = $gateway->purchase(
            [ 'biz_content' => [
                // TODO 更多参数
                'out_trade_no'      => $trade->id,
                'total_amount'      => $trade->amount->format(),
                'subject'           => $trade->subject,
                'product_code'      => $this->channelProduct->code,
                'merchant_order_no' => $trade->merchant_order_no,
            ] ]
        );

        /**
         * @var $response AbstractResponse
         */
        $response = $request->send();
        $result   = new ChannelResult;
        $result->setMessage($response->getMessage());
        $result->setCode($response->getCode());
        $result->setSuccessFul(false);
        $result->setData($response->getData());
        if ($response->isSuccessful()) {
            $result->setSuccessFul(true);
            if ($response instanceof AopTradePagePayResponse) {
                $result->setResult($response->getRedirectUrl());
            }
            if ($response instanceof AopTradeWapPayResponse) {
                $result->setResult($response->getRedirectUrl());
            }
            if ($response instanceof AopTradeAppPayResponse) {
                $result->setResult($response->getOrderString());
            }
            if ($response instanceof AopTradePreCreateResponse) {
                $result->setResult($response->getQrCode());
            }
            if ($response instanceof AopTradeCreateResponse) {
                $result->setResult($response->getTradeNo());
                $result->setTradeNo($response->getTradeNo());
            }
        }

        return $result;

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
        $result  = new ChannelResult;

        $result->setSuccessFul(false);
        try {
            /**
             * @var $response AopCompletePurchaseResponse
             */
            $response = $request->send();

            $result->setMessage($response->getMessage());
            $result->setCode($response->getCode());
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
                $result->setSuccessFul(true);
                $channelTradeData                     = new  ChannelTradeData;
                $channelTradeData->originalParameters = $data;
                $channelTradeData->channelCode        = $this->channelApp->channel_code;
                $channelTradeData->channelMerchantId  = $this->channelApp->channel_merchant_id;
                $channelTradeData->channelAppId       = (string)$data['app_id'];
                $channelTradeData->id                 = (int)$data['out_trade_no'];
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

    public function notifyResponse() : NotifyResponseInterface
    {
        return new   AlipayNotifyResponse();
    }


}
