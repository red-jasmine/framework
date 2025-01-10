<?php

namespace RedJasmine\Payment\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;
use RedJasmine\Payment\Domain\Repositories\MerchantChannelAppPermissionRepositoryInterface;

class MerchantChannelAppPermissionRepository implements MerchantChannelAppPermissionRepositoryInterface
{

    protected static string $eloquentModelClass = MerchantChannelAppPermission::class;

    public function find(int $merchantId, int $channelAppId) : ?MerchantChannelAppPermission
    {
        return static::$eloquentModelClass::where('merchant_id', $merchantId)
                                          ->where('channel_app_id', $channelAppId)
                                          ->first();
    }


    public function store(MerchantChannelAppPermission $model) : MerchantChannelAppPermission
    {
        $model->push();

        return $model;
    }

    public function findMerchantAuthorizedChannelApps(int $merchantId) : Collection
    {
        return static::$eloquentModelClass::with(['channelApp'])
                                          ->where('merchant_id', $merchantId)
                                          ->get();
    }


}
