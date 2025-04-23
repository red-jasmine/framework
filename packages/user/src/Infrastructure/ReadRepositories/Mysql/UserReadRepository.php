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
        return $this->query(null)->where('name', $name)->first();
    }

    public function findByEmail(string $email) : ?User
    {
        return $this->query()->where('email', AES::encryptString($email))->first();
    }

    public function findByMobile(string $mobile) : ?User
    {
        return $this->query()->where('mobile', AES::encryptString($mobile))->first();
    }


    public function findByAccount(string $account) : ?User
    {
        return $this->query()
                    ->where('name', $account)
                    ->orWhere('email', AES::encryptString($account))
                    ->orWhere('mobile', AES::encryptString($account))
                    ->first();
    }

    public function findByConditions($credentials) : ?User
    {
        return $this->query()->where($credentials)->first();
    }


}
