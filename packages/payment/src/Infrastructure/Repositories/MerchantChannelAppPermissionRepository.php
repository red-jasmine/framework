<?php

namespace RedJasmine\Payment\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;


class MerchantChannelAppPermissionRepository  implements MerchantChannelAppPermissionRepositoryInterface
{



    protected static string $modelClass = MerchantChannelAppPermission::class;

    public function find(int $merchantAppId, int $channelAppId) : ?MerchantChannelAppPermission
    {
        return static::$modelClass::where('merchant_app_id', $merchantAppId)
                                          ->where('channel_app_id', $channelAppId)
                                          ->first();
    }



    public function findMerchantAppAuthorizedChannelApps(int $merchantAppId) : Collection
    {
        return static::$modelClass::with(['channelApp'])
                                          ->where('merchant_app_id', $merchantAppId)
                                          ->get();
    }

    public function store(MerchantChannelAppPermission $permission) : void
    {
        $permission->push();
    }


}

