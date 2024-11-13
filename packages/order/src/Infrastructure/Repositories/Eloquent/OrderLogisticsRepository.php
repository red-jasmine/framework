<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;


use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class OrderLogisticsRepository extends EloquentRepository implements OrderLogisticsRepositoryInterface
{

    protected static string $eloquentModelClass = OrderLogistics::class;


}
