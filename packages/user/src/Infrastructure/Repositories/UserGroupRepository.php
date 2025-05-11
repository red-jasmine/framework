<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\User\Domain\Models\UserGroup;
use RedJasmine\User\Domain\Repositories\UserGroupRepositoryInterface;

class UserGroupRepository extends EloquentRepository implements UserGroupRepositoryInterface
{
    protected static string $eloquentModelClass = UserGroup::class;
}