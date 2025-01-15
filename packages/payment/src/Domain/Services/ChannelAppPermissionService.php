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

    )
    {
    }


    /**
     * @param int $merchantAppId
     * @return Collection<ChannelApp>
     */
    public function getAvailableChannelAppsByMerchantApp(int $merchantAppId) : Collection
    {
        $permissions = $this->repository->findMerchantAppAuthorizedChannelApps($merchantAppId);

        return $permissions->map(function (MerchantChannelAppPermission $permission) {
            if ($permission->isAvailable() && $permission->channelApp->isAvailable()) {
                return $permission->channelApp;
            }
        })->filter(function ($channelApp) {
            return $channelApp;

        });
    }


    /**
     * @param int $channelAppId
     * @param int $merchantId
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
