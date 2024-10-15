<?php

namespace RedJasmine\Product\Domain\Property\Repositories;


use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;


interface ProductPropertyReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findByIds(array $ids);

}
