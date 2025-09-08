<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\User\Domain\Models\UserTag;
use RedJasmine\User\Domain\Repositories\UserTagRepositoryInterface;

class UserTagRepository extends Repository implements UserTagRepositoryInterface
{
    protected static string $modelClass = UserTag::class;
}