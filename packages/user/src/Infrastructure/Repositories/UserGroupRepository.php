<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;

class UserGroupRepository extends Repository implements UserGroupRepositoryInterface
{
    protected static string $modelClass = UserGroup::class;
}