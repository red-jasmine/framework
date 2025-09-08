<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Property\Models\ProductProperty;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;


class ProductPropertyRepository extends Repository implements ProductPropertyRepositoryInterface
{
    protected static string $modelClass = ProductProperty::class;

    /**
     * 按名称查询
     *
     * @param string $name
     *
     * @return ProductProperty|null
     */
    public function findByName(string $name) : ?ProductProperty
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
