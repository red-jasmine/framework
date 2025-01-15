<?php

namespace RedJasmine\Payment\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Payment\Domain\Models\MerchantChannelAppPermission;


interface MerchantChannelAppPermissionRepositoryInterface
{


    public function find(int $merchantAppId, int $channelAppId) : ?MerchantChannelAppPermission;

    /**
     * 存储一个模型实例到数据库。
     *
     * @param  MerchantChannelAppPermission  $model  要存储的模型实例。
     *
     * @return mixed 存储操作的结果，具体返回类型取决于实现。
     */
    public function store(MerchantChannelAppPermission $model) : MerchantChannelAppPermission;

    /**
     * @param  int  $merchantAppId
     *
     * @return Collection<MerchantChannelAppPermission>
     */
    public function findMerchantAppAuthorizedChannelApps(int $merchantAppId) : Collection;

}
