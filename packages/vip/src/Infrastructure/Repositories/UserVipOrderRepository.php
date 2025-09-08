<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Vip\Domain\Models\UserVipOrder;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;

class UserVipOrderRepository extends Repository implements UserVipOrderRepositoryInterface
{

    protected static string $modelClass = UserVipOrder::class;

    public function stores(Collection $orders) : bool
    {
        foreach ($orders as $order) {
            $order->push();
        }
        return true;
    }


}