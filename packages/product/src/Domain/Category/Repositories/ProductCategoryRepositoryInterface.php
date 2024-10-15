<?php

namespace RedJasmine\Product\Domain\Category\Repositories;

use RedJasmine\Product\Domain\Category\Models\ProductCategory;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface ProductCategoryRepositoryInterface extends RepositoryInterface
{

    public function findByName($name) : ?ProductCategory;

}
