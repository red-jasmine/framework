<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class PointProductCategoryRepository extends Repository implements PointProductCategoryRepositoryInterface
{
    protected static string $modelClass = PointsProductCategory::class;

    public function findByName($name): ?PointsProductCategory
    {
        /** @var class-string<PointsProductCategory> $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::where('name', $name)->first();
    }
} 