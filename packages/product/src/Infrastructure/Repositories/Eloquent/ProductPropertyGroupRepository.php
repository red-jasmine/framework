<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;


use RedJasmine\Product\Domain\Property\Models\ProductPropertyGroup;
use RedJasmine\Product\Domain\Property\Repositories\ProductPropertyGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;


class ProductPropertyGroupRepository extends Repository implements ProductPropertyGroupRepositoryInterface
{
    protected static string $modelClass = ProductPropertyGroup::class;

    /**
     * @param string $name
     *
     * @return ProductPropertyGroup|null
     */
    public function findByName(string $name) : ?ProductPropertyGroup
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
