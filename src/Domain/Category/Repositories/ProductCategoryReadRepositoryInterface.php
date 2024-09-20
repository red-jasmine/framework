<?php

namespace RedJasmine\Product\Domain\Category\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductCategoryReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Query $query) : array;

}
