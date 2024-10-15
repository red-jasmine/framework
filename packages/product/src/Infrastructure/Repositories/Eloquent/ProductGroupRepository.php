<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class ProductGroupRepository extends EloquentRepository implements ProductGroupRepositoryInterface
{

    protected static string $eloquentModelClass = ProductGroup::class;

}
