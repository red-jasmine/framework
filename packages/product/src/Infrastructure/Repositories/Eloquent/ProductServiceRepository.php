<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Product\Domain\Service\Repositories\ProductServiceRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ProductServiceRepository extends EloquentRepository implements ProductServiceRepositoryInterface
{

    protected static string $eloquentModelClass = ProductService::class;

}
