<?php

namespace RedJasmine\Order\Domain\Repositories;


use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 订单物流仓库接口
 *
 * 提供订单物流实体的读写操作统一接口
 *
 * @method OrderLogistics find($id)
 */
interface OrderLogisticsRepositoryInterface extends RepositoryInterface
{
    // 合并了原OrderLogisticsReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
