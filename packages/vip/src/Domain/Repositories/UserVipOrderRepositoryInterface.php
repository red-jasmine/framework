<?php

namespace RedJasmine\Vip\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\Vip\Domain\Models\UserVipOrder;

/**
 * 用户VIP订单仓库接口
 *
 * 提供用户VIP订单实体的读写操作统一接口
 *
 * @method UserVipOrder find($id)
 */
interface UserVipOrderRepositoryInterface extends RepositoryInterface
{
    /**
     * 批量存储订单
     */
    public function stores(Collection $orders): bool;
}