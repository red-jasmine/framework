<?php

namespace RedJasmine\Payment\Domain\Services;

use App\Console\Commands\Test;
use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\ChannelApp;
use RedJasmine\Payment\Domain\Models\MerchantApp;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;

/**
 * 渠道应用权限服务
 */
class ChannelAppPermissionService
{

    public function __construct(
        protected MerchantChannelAppPermissionRepositoryInterface $repository

    ) {
    }


    public function getAvailableChannelAppsByMerchantApp(MerchantApp $merchantApp) : Collection
    {
        // 获取商户可用的支付渠道应用
        $channelApps = $this->getAvailableChannelAppsByMerchant($merchantApp->merchant_id);


        // TODO
        // 如果应用设置为 复用商户权限，那么就直接使用商户的权限

        // 如果应用设置为单独，那么就获取应用的的权限


        return $channelApps;
    }

    /**
     * @param  int  $merchantId
     *
     * @return Collection<ChannelApp>
     */
    public function getAvailableChannelAppsByMerchant(int $merchantId) : Collection
    {
        // 获取商户可用的支付渠道应用

        $permissions = $this->repository->findMerchantAuthorizedChannelApps($merchantId);

        return $permissions->map(function (MerchantChannelAppPermission $permission) {
            if ($permission->isAvailable() && $permission->channelApp->isAvailable()) {
                return $permission->channelApp;
            }
        })->filter(function ($channelApp) {
            return $channelApp;

        });


    }


    /**
     * @param  int  $channelAppId
     * @param  int  $merchantId
     *
     * @return void
     * @throws PaymentException
     */
    public function verifyMerchantPermission(int $channelAppId, int $merchantId) : void
    {
        $permission = $this->repository->find($channelAppId, $merchantId);
        if ($permission && $permission->isAvailable()) {
            throw new PaymentException('商户没有渠道应用权限');
        }
    }

}
