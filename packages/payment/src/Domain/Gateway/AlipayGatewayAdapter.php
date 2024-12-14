<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Alipay\AopAppGateway;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Trade;

/**
 * 渠道适配器
 */
class AlipayGatewayAdapter implements GatewayAdapterInterface
{

    protected GatewayInterface $gateway;


    public function init(ChannelApp $channelApp, ChannelProduct $channelProduct) : static
    {
        // 主要是用 Alipay 底下网关

        /**
         * @var $gateway AopAppGateway
         */
        $this->gateway = $gateway = Omnipay::create($channelProduct->gateway);

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

        return $this;
    }

    public function purchase(Trade $trade) : RequestInterface
    {
        $gateway = $this->gateway;
        // 渠道通知地址
        $gateway->setNotifyUrl();
        return $this->gateway->purchase([]);
    }


}
