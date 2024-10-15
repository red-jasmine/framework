<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;


class ProductPropertyRepository extends EloquentRepository implements ProductPropertyRepositoryInterface
{
    protected static string $eloquentModelClass = ProductProperty::class;

    /**
     * 按名称查询
     *
     * @param string $name
     *
     * @return ProductProperty|null
     */
    public function findByName(string $name) : ?ProductProperty
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
