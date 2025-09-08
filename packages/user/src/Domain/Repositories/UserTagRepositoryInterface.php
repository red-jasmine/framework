<?php

namespace RedJasmine\User\Domain\Repositories;

use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface UserTagRepositoryInterface extends RepositoryInterface
{
    public function tree(Query $query) : array;
}