<?php

namespace RedJasmine\Payment\Infrastructure\Gateway;

use Dflydev\DotAccessData\Data;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Omnipay\Alipay\AbstractAopGateway;
use Omnipay\Alipay\AopAppGateway;
use Omnipay\Alipay\AopPageGateway;
use Omnipay\Common\GatewayInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Omnipay;
use RedJasmine\Payment\Domain\Facades\PaymentUrl;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelResult;
use RedJasmine\Payment\Domain\Gateway\Data\PaymentChannelData;
use RedJasmine\Payment\Domain\Gateway\Data\Purchase;
use RedJasmine\Payment\Domain\Gateway\Data\PurchaseResult;
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

        $money = new Money($trade->amount_value, new Currency($trade->amount_currency));

        $currencies     = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        /**
         * @var $gateway AopPageGateway
         */
        $gateway = $this->gateway;

        $gateway->setReturnUrl(PaymentUrl::returnUrl($trade));

        $request = $gateway->purchase(
            [ 'biz_content' => [
                'out_trade_no'      => $trade->id,
                'total_amount'      => $moneyFormatter->format($money),
                'subject'           => $trade->subject,
                'product_code'      => $this->channelProduct->code,
                'merchant_order_no' => $trade->merchant_order_no,
            ] ]
        );

        $response = $request->send();

        $result = new ChannelResult;
        $result->setSuccessFul(false);
        if ($response->isSuccessful()) {
            $result->setSuccessFul(true);
            // TODO 根据不同的返回
            $result->setResult($response->getRedirectUrl());
        }

        return $result;

    }


}
