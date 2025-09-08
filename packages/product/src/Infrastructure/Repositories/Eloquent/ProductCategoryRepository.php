<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Product\Domain\Category\Repositories\ProductCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProductCategoryRepository extends Repository implements ProductCategoryRepositoryInterface
{

    protected static string $modelClass = ProductCategory::class;

    public function findByName($name) : ?ProductCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
