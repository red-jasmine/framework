<?php

namespace RedJasmine\Product\Domain\Stock\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductSkuReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findList(array $ids);

}
