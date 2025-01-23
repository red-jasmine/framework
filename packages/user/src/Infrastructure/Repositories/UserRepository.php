<?php

namespace RedJasmine\User\Infrastructure\Repositories;

use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{

    protected static string $eloquentModelClass = User::class;

    public function findByName(string $name) : ?User
    {
        return static::$eloquentModelClass::where('name', $name)->first();
    }


}
