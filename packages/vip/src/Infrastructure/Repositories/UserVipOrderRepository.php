<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Vip\Domain\Models\UserVipOrder;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;

/**
 * 用户VIP订单仓库实现
 *
 * 基于Repository实现，提供用户VIP订单实体的读写操作能力
 */
class UserVipOrderRepository extends Repository implements UserVipOrderRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = UserVipOrder::class;

    /**
     * 批量存储订单
     */
    public function stores(Collection $orders): bool
    {
        foreach ($orders as $order) {
            $order->push();
        }
        return true;
    }
}