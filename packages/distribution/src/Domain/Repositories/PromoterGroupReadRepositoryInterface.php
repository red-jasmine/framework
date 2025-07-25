<?php

namespace RedJasmine\Distribution\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PromoterGroupReadRepositoryInterface extends ReadRepositoryInterface
{
    public function tree(Query $query) : array;
}