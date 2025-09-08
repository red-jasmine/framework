<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;

class UserRepository extends Repository implements UserRepositoryInterface
{

    protected static string $modelClass = User::class;

    public function findByName(string $name) : ?User
    {
        return static::$modelClass::where('name', $name)->first();
    }


}
