<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ProductCategoryRepository extends EloquentRepository implements ProductCategoryRepositoryInterface
{

    protected static string $eloquentModelClass = ProductCategory::class;

    public function findByName($name) : ?ProductCategory
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
