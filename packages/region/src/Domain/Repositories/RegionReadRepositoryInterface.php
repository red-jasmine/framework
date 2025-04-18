<?php

namespace RedJasmine\Region\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface RegionReadRepositoryInterface extends ReadRepositoryInterface
{
    public function tree(?Query $query) : array;

    public function children(?Query $query) : array;


}