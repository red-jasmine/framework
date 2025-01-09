<?php

namespace RedJasmine\Payment\Domain\Services;

use RedJasmine\Payment\Domain\Exceptions\PaymentException;
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
