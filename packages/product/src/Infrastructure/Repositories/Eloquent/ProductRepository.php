<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Product\Repositories\ProductRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    protected static string $modelClass = Product::class;

}
