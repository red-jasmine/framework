<?php

namespace RedJasmine\User\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface UserGroupReadRepositoryInterface extends ReadRepositoryInterface
{
    public function tree(Query $query) : array;
}