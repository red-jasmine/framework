<?php

namespace RedJasmine\User\Infrastructure\ReadRepositories\Mysql;


use RedJasmine\Support\Facades\AES;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use RedJasmine\User\Domain\Models\User;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;

class UserReadRepository extends QueryBuilderReadRepository implements UserReadRepositoryInterface
{

    public static string $modelClass = User::class;

    public function findByName(string $name) : ?User
    {
        return $this->query()->where('name', $name)->first();
    }

    public function findByEmail(string $email) : ?User
    {
        return $this->query()->where('email', AES::encryptString($email))->first();
    }

    public function findByPhone(string $phone) : ?User
    {
        return $this->query()->where('phone', AES::encryptString($phone))->first();
    }


    public function findByAccount(string $account) : ?User
    {
        return $this->query()
                    ->where('name', $account)
                    ->orWhere('email', AES::encryptString($account))
                    ->orWhere('phone', AES::encryptString($account))
                    ->first();
    }

    public function findByConditions($credentials) : ?User
    {
        return $this->query()->where($credentials)->first();
    }


}
