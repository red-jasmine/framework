<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;


use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class OrderRepository extends Repository implements OrderRepositoryInterface
{

    protected static string $modelClass = Order::class;

    public function findByNo(string $no) : Order
    {

        return static::$modelClass::where('order_no', $no)->firstOrFail();

    }


}
