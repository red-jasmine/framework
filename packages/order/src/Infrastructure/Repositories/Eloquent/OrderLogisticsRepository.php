<?php

namespace RedJasmine\Order\Infrastructure\Repositories\Eloquent;


use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class OrderLogisticsRepository extends Repository implements OrderLogisticsRepositoryInterface
{

    protected static string $modelClass = OrderLogistics::class;


}
