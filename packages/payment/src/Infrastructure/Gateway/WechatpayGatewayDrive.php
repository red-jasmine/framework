<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
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
use RedJasmine\Payment\Domain\Models\Enums\SignMethodEnum;
use RedJasmine\Payment\Domain\Models\Refund;
use RedJasmine\Payment\Domain\Models\Settle;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Payment\Domain\Models\Trade;
use RedJasmine\Payment\Domain\Models\Transfer;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;

class WechatpayGatewayDrive implements GatewayDriveInterface
{
    public function gateway(PaymentChannelData $paymentChannelData) : static
    {
        $this->paymentChannelData = $paymentChannelData;
        $this->channelProduct     = $paymentChannelData->channelProduct;
        $this->channelApp         = $paymentChannelData->channelApp;
        $gatewayName              = $this->channelProduct->gateway ?? 'WechatPay';
        $this->gateway            = $this->initChannelApp(Omnipay::create($gatewayName), $this->channelApp);

        return $this;

    }

    /**
     * @param GatewayInterface $gateway
     * @param ChannelApp $channelApp
     *
     * @return GatewayInterface
     * @throws InvalidRequestException
     */
    protected function initChannelApp(GatewayInterface $gateway, ChannelApp $channelApp) : GatewayInterface
    {
        /**
         * @var $gateway \Omnipay\WechatPay\Gateway
         */


        $gateway->setAppId($channelApp->channel_app_id);
        $gateway->setMchId($channelApp->channel_merchant_id);
        $gateway->setPrivateKey($channelApp->channel_app_private_key);
        $gateway->setNotifyUrl(PaymentUrl::notifyUrl($channelApp));

        if ($channelApp->isSandbox()) {
            $gateway->sandbox();
        }
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


    public function purchase(Trade $trade, Environment $environment) : ChannelPurchaseResult
    {
        // TODO: Implement purchase() method.
    }

    public function refund(Refund $refund) : ChannelRefundResult
    {
        // TODO: Implement refund() method.
    }

    public function refundQuery(Refund $refund) : ChannelRefundQueryResult
    {
        // TODO: Implement refundQuery() method.
    }

    public function completePurchase(array $parameters = []) : ChannelTradeData
    {
        // TODO: Implement completePurchase() method.
    }

    public function notifyResponse() : NotifyResponseInterface
    {
        // TODO: Implement notifyResponse() method.
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