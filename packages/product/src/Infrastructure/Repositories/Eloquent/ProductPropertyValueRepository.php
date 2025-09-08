<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;


class ProductPropertyValueRepository extends Repository implements ProductPropertyValueRepositoryInterface
{
    protected static string $modelClass = ProductPropertyValue::class;

    public function findByNameInProperty(int $pid, string $name) : ?ProductPropertyValue
    {
        return static::$modelClass::where('pid', $pid)->where('name', $name)->first();
    }


}
