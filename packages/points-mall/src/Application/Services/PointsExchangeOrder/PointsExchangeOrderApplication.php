<?php

namespace RedJasmine\PointsMall\Application\Services\PointsExchangeOrder;

use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\Support\Application\ApplicationService;

class PointsExchangeOrderApplication extends ApplicationService
{
    public static string    $hookNamePrefix = 'points-mall.product.application';
    protected static string $modelClass     = PointsExchangeOrder::class;

}