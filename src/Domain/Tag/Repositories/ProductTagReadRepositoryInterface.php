<?php

namespace RedJasmine\Product\Domain\Tag\Repositories;

use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductTagReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByName($name) : ?ProductTag;

}
