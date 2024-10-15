<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;


use Illuminate\Support\Facades\DB;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use Throwable;

class OrderRepository extends EloquentRepository implements OrderRepositoryInterface
{

    protected static string $eloquentModelClass = Order::class;


}
