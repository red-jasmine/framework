<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Omnipay\Alipay\AopPageGateway;
use Omnipay\Alipay\Responses\AbstractResponse;
use Omnipay\Alipay\Responses\AopTradeAppPayResponse;
use Omnipay\Alipay\Responses\AopTradeCreateResponse;
use Omnipay\Alipay\Responses\AopTradePagePayResponse;
use Omnipay\Alipay\Responses\AopTradePreCreateResponse;
use Omnipay\Alipay\Responses\AopTradeWapPayResponse;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\GatewayDriveInterface;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

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

    protected function getPublicKey($cert) : string
    {
        $pkey       = openssl_pkey_get_public($cert);
        $keyData    = openssl_pkey_get_details($pkey);
        $public_key = str_replace('-----BEGIN PUBLIC KEY-----', '', $keyData['key']);
        return trim(str_replace('-----END PUBLIC KEY-----', '', $public_key));
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


}
