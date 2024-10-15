<?php

namespace RedJasmine\Product\Domain\Brand\Repositories;


use RedJasmine\Product\Domain\Brand\Models\Brand;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


interface BrandRepositoryInterface extends RepositoryInterface
{


    public function findByName($name) : ?Brand;

}
