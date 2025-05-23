<?php

namespace RedJasmine\User\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface UserTagReadRepositoryInterface extends ReadRepositoryInterface
{
    public function tree(Query $query) : array;
}