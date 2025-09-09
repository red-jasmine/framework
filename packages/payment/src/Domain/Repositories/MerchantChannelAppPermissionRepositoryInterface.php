<?php

namespace RedJasmine\Payment\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method MerchantChannelAppPermission find($id)
 */
interface MerchantChannelAppPermissionRepositoryInterface extends RepositoryInterface
{
    public function find(int $merchantAppId, int $channelAppId) : ?MerchantChannelAppPermission;

    /**
     * @param  int  $merchantAppId
     *
     * @return Collection<MerchantChannelAppPermission>
     */
    public function findMerchantAppAuthorizedChannelApps(int $merchantAppId) : Collection;

}

