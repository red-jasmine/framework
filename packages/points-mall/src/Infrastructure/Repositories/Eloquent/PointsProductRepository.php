<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PointsProductRepository extends Repository implements PointsProductRepositoryInterface
{
    protected static string $modelClass = PointsProduct::class;
} 