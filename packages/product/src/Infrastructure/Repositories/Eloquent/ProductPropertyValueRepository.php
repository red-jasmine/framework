<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyValue;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyValueRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;


class ProductPropertyValueRepository extends EloquentRepository implements ProductPropertyValueRepositoryInterface
{
    protected static string $eloquentModelClass = ProductPropertyValue::class;

    public function findByNameInProperty(int $pid, string $name) : ?ProductPropertyValue
    {
        return static::$eloquentModelClass::where('pid', $pid)->where('name', $name)->first();
    }


}
