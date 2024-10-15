<?php

namespace RedJasmine\Product\Domain\Property\Repositories;


use RedJasmine\Support\Domain\Repositories\RepositoryInterface;


interface ProductPropertyGroupRepositoryInterface extends RepositoryInterface
{
    public function findByName(string $name);
}
