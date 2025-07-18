<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Illuminate\Support\Carbon;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use Omnipay\WechatPay\Gateway;
use Omnipay\WechatPay\Message\CreateOrderResponse;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Data\PaymentTrigger;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelPurchaseResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelRefundResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleReceiverQuery;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleReceiverQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelTransferQueryResult;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelTransferResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\GatewayDriveInterface;
use RedJasmine\Payment\Domain\Gateway\NotifyResponseInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\Enums\PaymentTriggerTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use Cknow\Money\Money;
use Throwable;

class WechatPayGatewayDrive implements GatewayDriveInterface
{

    public $gateway;

    public function initGateway(PaymentChannelData $paymentChannelData) : static
    {
        $this->paymentChannelData = $paymentChannelData;
        $this->channelProduct     = $paymentChannelData->channelProduct;
        $this->channelApp         = $paymentChannelData->channelApp;
        $gatewayName              = $this->channelProduct->gateway ?? 'WechatPay';
        $this->gateway            = $this->initChannelApp(Omnipay::create($gatewayName), $this->channelApp);
        return $this;

    }

    /**
     * @param  GatewayInterface  $gateway
     * @param  ChannelApp  $channelApp
     *
     * @return GatewayInterface
     * @throws InvalidRequestException
     */
    protected function initChannelApp(GatewayInterface $gateway, ChannelApp $channelApp) : GatewayInterface
    {
        /**
         * @var $gateway Gateway
         */


        $gateway->setAppId($channelApp->channel_app_id);
        $gateway->setMchId($channelApp->channel_merchant_id);
        $gateway->setPrivateKey($channelApp->channel_app_private_key);
        $gateway->setNotifyUrl(PaymentUrl::notifyUrl($channelApp));


        switch ($channelApp->sign_method) {
            case SignMethodEnum::Secret:
                if (blank($channelApp->channel_public_key)) {
                    throw new RuntimeException('公钥证书不存在');
                }
                //$gateway->setAlipayPublicKey($channelApp->channel_public_key);
                break;
            case SignMethodEnum::Cert:
                if (blank($channelApp->channel_app_public_key_cert)) {
                    throw new RuntimeException('应用证书不存在');
                }
                $gateway->setAppCert($channelApp->channel_app_public_key_cert);

                $gateway->setChannelCert($channelApp->channel_public_key_cert);
                $gateway->setChannelPublicKey($channelApp->channel_public_key);

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

    public function notifyResponse() : NotifyResponseInterface
    {
        return new   NotifyResponse();
    }

    /**
     * @param  Trade  $trade
     * @param  Environment  $environment
     *
     * @return ChannelPurchaseResult
     * @throws Throwable
     */
    public function purchase(Trade $trade, Environment $environment) : ChannelPurchaseResult
    {
        /**
         * @var $gateway Gateway
         */

        $gateway = $this->gateway;

        if (method_exists($gateway, 'setReturnUrl')) {
            $gateway->setReturnUrl(PaymentUrl::returnUrl($trade));
        }
        switch ($environment->scene) {
            case SceneEnum::APP:
                $gateway->setTradeType('APP');
                break;
            case SceneEnum::WEB:
                $gateway->setTradeType('WEB');
                break;
            case SceneEnum::WAP:
                $gateway->setTradeType('WAP');
                break;
            case SceneEnum::JSAPI:
                $gateway->setTradeType('JSAPI');
                break;
            case SceneEnum::QRCODE:
            case SceneEnum::FACE:
            case SceneEnum::PROTOCOL:
            case SceneEnum::API:
            default:
                throw new \RuntimeException('To be implemented');
        }


        $data = [
            'description'  => $trade->subject,
            'out_trade_no' => $trade->trade_no,
            'time_expire'  => $trade->expired_time?->toIso8601String(),
            'attach'       => '', // 商户数据包
            'amount'       => bcmul($trade->amount->value, 100, 0),
            'currency'     => $trade->amount->currency,
            'payer'        => [
                'openid' => $environment->payer?->openId,
            ],
        ];

        $request = $gateway->purchase($data);


        try {

            /**
             * @var $response CreateOrderResponse
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

                switch ($environment->scene) {
                    case SceneEnum::APP:
                        $paymentTrigger->type    = PaymentTriggerTypeEnum::APP;
                        $paymentTrigger->content = $response->getAppOrderData();
                        break;
                    case SceneEnum::WEB:
                        $paymentTrigger->type    = PaymentTriggerTypeEnum::QR_CODE;
                        $paymentTrigger->content = $response->getCodeUrl();
                        break;
                    case SceneEnum::WAP:
                        $paymentTrigger->type    = PaymentTriggerTypeEnum::REDIRECT;
                        $paymentTrigger->content = $response->getWapUrl();
                        break;
                    case SceneEnum::JSAPI:
                        $paymentTrigger->type    = PaymentTriggerTypeEnum::APPLET;
                        $paymentTrigger->content = $response->getJsOrderData();
                        break;
                    case SceneEnum::QRCODE:
                        $paymentTrigger->type = PaymentTriggerTypeEnum::QR_CODE;
                        break;
                    case SceneEnum::PROTOCOL:
                    case SceneEnum::FACE:
                    case SceneEnum::API:
                    default:
                        throw new \RuntimeException('To be implemented');
                }

                $result->paymentTrigger = $paymentTrigger;
            }
            return $result;
        } catch (Throwable $throwable) {
            report($throwable);
            throw $throwable;

        }
    }

    public function completePurchase(array $parameters = []) : ChannelTradeData
    {
        /**
         * @var $gateway Gateway
         */

        $gateway = $this->gateway;

        $request = $gateway->completePurchase(['request_params' => $parameters]);
        try {

            $response = $request->send();


            if ($response->isPaid()) {
                $data = $response->getData();
                $data['data']['out_trade_no'];
                $data['data']['transaction_id'];
                $data['data']['trade_state'];
                $data['data']['attach'];
                $data['data']['success_time'];
                $data['data']['amount'];

                // 调用查询接口
                $queryRequest = $gateway->query(['out_trade_no' => $data['data']['out_trade_no']]);

                $queryResponse = $queryRequest->send();
                if ($queryResponse->isSuccessful()) {
                    // 合并参数
                    $data = array_merge($data, $queryResponse->getData());
                }
                $data = $data['data'] ?? [];

                // TODO 转换查询对象
                $channelTradeData                     = new  ChannelTradeData;
                $channelTradeData->originalParameters = $data;
                $channelTradeData->channelCode        = $this->channelApp->channel_code;
                $channelTradeData->channelMerchantId  = $this->channelApp->channel_merchant_id;
                $channelTradeData->channelAppId       = (string) $data['appid'];
                $channelTradeData->tradeNo            = (string) $data['out_trade_no'];
                $channelTradeData->channelTradeNo     = (string) $data['transaction_id'];
                $channelTradeData->amount             = new Money(bcdiv($data['amount']['total'], 100, 2));
                $channelTradeData->paymentAmount      = new Money(bcdiv($data['amount']['payer_total'], 100, 2));
                $channelTradeData->status             = TradeStatusEnum::SUCCESS;
                $channelTradeData->payer              = Payer::from([
                    'type'    => $data['payer']['buyer_user_type'] ?? null,
                    'userId'  => $data['payer']['buyer_id'] ?? null,
                    'account' => $data['payer']['buyer_logon_id'] ?? null,
                    'openId'  => $data['payer']['openid'] ?? null,
                    'name'    => null,
                ]);
                $channelTradeData->paidTime           = Carbon::parse($data['success_time'], 'Asia/Shanghai');
                return $channelTradeData;
            }
        } catch (Exception $e) {
            throw $e;

        }


    }


    public function refund(Refund $refund) : ChannelRefundResult
    {
        // TODO: Implement refund() method.
    }

    public function refundQuery(Refund $refund) : ChannelRefundQueryResult
    {
        // TODO: Implement refundQuery() method.
    }


    public function transfer(Transfer $transfer) : ChannelTransferResult
    {
        // TODO: Implement transfer() method.
    }

    public function transferQuery(Transfer $transfer) : ChannelTransferQueryResult
    {
        // TODO: Implement transferQuery() method.
    }

    public function bindSettleReceiver(SettleReceiver $settleReceiver) : ChannelResult
    {
        // TODO: Implement bindSettleReceiver() method.
    }

    public function unbindSettleReceiver(SettleReceiver $settleReceiver) : ChannelResult
    {
        // TODO: Implement unbindSettleReceiver() method.
    }

    public function querySettleReceivers(ChannelSettleReceiverQuery $query = new ChannelSettleReceiverQuery
    ) : ChannelSettleReceiverQueryResult {
        // TODO: Implement querySettleReceivers() method.
    }

    public function settle(Settle $settle) : ChannelSettleResult
    {
        // TODO: Implement settle() method.
    }


}