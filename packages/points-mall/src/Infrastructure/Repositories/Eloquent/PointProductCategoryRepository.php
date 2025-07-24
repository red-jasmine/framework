<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent;

use RedJasmine\PointsMall\Domain\Models\PointsProductCategory;
use RedJasmine\PointsMall\Domain\Repositories\PointProductCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PointProductCategoryRepository extends EloquentRepository implements PointProductCategoryRepositoryInterface
{
    protected static string $eloquentModelClass = PointsProductCategory::class;

    public function findByName($name): ?PointsProductCategory
    {
        /** @var class-string<PointsProductCategory> $modelClass */
        $modelClass = static::$eloquentModelClass;
        return $modelClass::where('name', $name)->first();
    }
} 