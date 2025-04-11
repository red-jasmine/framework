<?php

namespace RedJasmine\User\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;

class UserReadRepository extends QueryBuilderReadRepository implements UserReadRepositoryInterface
{

    public static string $modelClass = User::class;

    public function findByName(string $name) : ?User
    {
        return $this->query(null)->where('name', $name)->first();
    }

    public function findByAccount(string $account) : ?User
    {
        return $this->query()->whereAny(['name', 'email', 'mobile'], $account)->first();
    }

    public function findByConditions($credentials) : ?User
    {
        return $this->query()->where($credentials)->first();
    }


}
