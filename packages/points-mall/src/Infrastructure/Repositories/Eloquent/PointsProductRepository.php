<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;
use RedJasmine\PointsMall\Domain\Repositories\PointsProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PointsProductRepository extends EloquentRepository implements PointsProductRepositoryInterface
{
    protected static string $eloquentModelClass = PointsProduct::class;
} 