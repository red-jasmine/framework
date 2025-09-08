<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProductServiceRepository extends Repository implements ProductServiceRepositoryInterface
{

    protected static string $modelClass = ProductService::class;

}
