<?php

namespace RedJasmine\Product\Domain\Service\Repositories;


use RedJasmine\Product\Domain\Service\Models\ProductService;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface ProductServiceReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByName($name) : ?ProductService;

}
