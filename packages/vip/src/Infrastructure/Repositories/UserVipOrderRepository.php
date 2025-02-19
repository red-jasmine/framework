<?php

namespace RedJasmine\Vip\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Vip\Domain\Models\UserVipOrder;
use RedJasmine\Vip\Domain\Repositories\UserVipOrderRepositoryInterface;

class UserVipOrderRepository extends EloquentRepository implements UserVipOrderRepositoryInterface
{

    protected static string $eloquentModelClass = UserVipOrder::class;

    public function stores(Collection $orders) : bool
    {
        foreach ($orders as $order) {
            $order->push();
        }
        return true;
    }


}