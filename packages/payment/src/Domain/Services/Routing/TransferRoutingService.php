<?php

namespace RedJasmine\Payment\Domain\Services\Routing;

use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Models\Transfer;

/**
 * 转账路由服务
 */
class TransferRoutingService
{

    public function __construct(

    )
    {
    }
    // 根据渠道 获取 符合的 应用
    // 根据应用 选择服务的 签约产品
    public function getChannelApp(Transfer $transfer,MerchantApp $merchantApp)
    {
        // 路由应用
        // 如果注定了渠道应用  那么需要查看是否有权限
        // 更具渠道 选择
        // 验证权限 商户是否有使用 渠道应用的权限
        // TODO
        $this->channelAppPermissionService
            ->verifyMerchantPermission(
                $transfer->payment_channel_app_id,
                $merchantApp->merchant_id
            );

    }

}
