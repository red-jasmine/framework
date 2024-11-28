<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;
use RedJasmine\Payment\Domain\Models\PaymentMerchantApp;

class PaymentPlatformRouteService
{

    public function getPlatforms(PaymentMerchantApp $merchantApp, PaymentEnvironmentData $paymentEnvironment) : array
    {
        // TODO
        // 获取当前 商户应用 允许的 渠道应用列表
        // 根据 渠道应用的支付配置  列出所有 的 支付平台
        // 更具平台设置 哪些是可选的

        return [];
    }

}
