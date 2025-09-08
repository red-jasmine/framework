<?php

namespace RedJasmine\Product\Infrastructure\Repositories\Eloquent;

use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Product\Domain\Group\Repositories\ProductGroupRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class ProductGroupRepository extends Repository implements ProductGroupRepositoryInterface
{

    protected static string $modelClass = ProductGroup::class;

}
