<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Alipay\AopAppGateway;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\Data\PurchaseResult;
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

    protected GatewayInterface $gateway;

    public function gateway(ChannelApp $channelApp, ?ChannelProduct $channelProduct = null) : static
    {
        $gatewayName = $channelProduct->gateway ?? 'Alipay_AopApp';
        /**
         * @var $gateway AopAppGateway
         */
        $this->gateway = $gateway = Omnipay::create($gatewayName);

        $this->initChannelApp($gateway, $channelApp);

        return $gateway;
    }


    protected function initChannelApp(GatewayInterface $gateway, ChannelApp $channelApp) : GatewayInterface
    {
        /**
         * @var $gateway AopAppGateway
         */
        $gateway->setAppId($channelApp->channel_app_id);
        $gateway->setPrivateKey($channelApp->channel_app_private_key);


        $gateway->setSignType($channelApp->sign_type);
        if ($channelApp->sign_method === SignMethodEnum::Secret) {
            $gateway->setAlipayPublicKey();
        }
        if ($channelApp->sign_method === SignMethodEnum::Cert) {

            $gateway->setAlipayRootCert();
            $gateway->setAlipayPublicCert();
            $gateway->setAppCert();
            $gateway->setCheckAlipayPublicCert(true);
        }
        // 内容加密
        $gateway->setEncryptKey($channelApp->encrypt_key);

        return $gateway;
    }

    public function purchase(Trade $trade, Environment $environment) : PurchaseResult
    {
        // TODO: Implement purchase() method.
    }


}
