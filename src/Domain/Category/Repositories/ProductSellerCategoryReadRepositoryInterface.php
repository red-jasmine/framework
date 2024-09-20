<?php

namespace RedJasmine\Product\Domain\Category\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductSellerCategoryReadRepositoryInterface extends ReadRepositoryInterface
{

    public function tree(Query $query) : array;

}
