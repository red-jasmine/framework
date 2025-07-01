<?php

namespace RedJasmine\Product\Domain\Product\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findList(array $ids);


    public function findSkuById(int $skuId);
}
