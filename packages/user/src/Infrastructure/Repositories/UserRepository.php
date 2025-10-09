<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;
use RedJasmine\UserCore\Infrastructure\Repositories\BaseUserRepository;

class UserRepository extends BaseUserRepository implements UserRepositoryInterface
{
    protected static string $modelClass = User::class;

}
