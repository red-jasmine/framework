<?php

namespace RedJasmine\Payment\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


interface MerchantChannelAppPermissionRepositoryInterface
{
    public function find(int $merchantAppId, int $channelAppId) : ?MerchantChannelAppPermission;

    /**
     * @param  int  $merchantAppId
     *
     * @return Collection<MerchantChannelAppPermission>
     */
    public function findMerchantAppAuthorizedChannelApps(int $merchantAppId) : Collection;

    public function store(MerchantChannelAppPermission $permission);
}

