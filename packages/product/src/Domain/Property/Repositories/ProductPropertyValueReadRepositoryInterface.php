<?php

namespace RedJasmine\Product\Domain\Property\Repositories;


use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;


interface ProductPropertyValueReadRepositoryInterface extends ReadRepositoryInterface
{

    public function findByIdsInProperty(int $pid, array $ids);

}
