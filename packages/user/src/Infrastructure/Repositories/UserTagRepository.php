<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\User\Domain\Models\UserTag;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;

class UserTagRepository extends EloquentRepository implements UserTagRepositoryInterface
{
    protected static string $eloquentModelClass = UserTag::class;
}