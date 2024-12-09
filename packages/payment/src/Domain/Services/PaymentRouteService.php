<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;
use RedJasmine\Payment\Domain\Models\MerchantApp;

/**
 * 交易路由
 */
class PaymentRouteService
{


    /**
     * 获取支付渠道
     * @param MerchantApp $merchantApp
     * @param PaymentEnvironmentData $paymentEnvironment
     * @return array
     */
    public function getMethods(MerchantApp $merchantApp, PaymentEnvironmentData $paymentEnvironment) : array
    {
        // TODO
        // 获取当前 商户应用 允许的 渠道应用列表、根据应用开通的产品、产品支付的方式 枚举出所有的支付方式

        $merchant = $merchantApp->merchant;


        $channelApps = [];

        $channelAppProducts = [];

        $channelAppProductsModes = [];

        // 根据 渠道应用的支付配置  列出所有 的 支付方式

        // 更具平台设置 哪些是可选的

        return [];
    }


    public function getChannelApp(MerchantApp $merchantApp, PaymentEnvironmentData $paymentEnvironment)
    {
        // 根据选择的  支付方式、支付场景

        // 获取可选的渠道应用

        // 选定 一个支付应用
    }

}
