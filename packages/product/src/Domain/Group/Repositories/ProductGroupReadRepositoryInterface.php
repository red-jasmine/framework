<?php

namespace RedJasmine\Product\Domain\Group\Repositories;

use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductGroupReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Query $query) : array;

    public function findByName($name) : ?ProductGroup;

}
