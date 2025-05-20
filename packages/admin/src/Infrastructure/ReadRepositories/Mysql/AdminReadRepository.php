<?php

namespace RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminReadRepositoryInterface;
use RedJasmine\Support\Facades\AES;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;

class AdminReadRepository extends QueryBuilderReadRepository implements AdminReadRepositoryInterface
{

    public static string $modelClass = Admin::class;

    public function findByName(string $name) : ?Admin
    {
        return $this->query(null)->where('name', $name)->first();
    }

    public function findByEmail(string $email) : ?Admin
    {
        return $this->query()->where('email', AES::encryptString($email))->first();
    }

    public function findByPhone(string $phone) : ?Admin
    {
        return $this->query()->where('phone', AES::encryptString($phone))->first();
    }


    public function findByAccount(string $account) : ?Admin
    {
        return $this->query()
                    ->where('name', $account)
                    ->orWhere('email', AES::encryptString($account))
                    ->orWhere('phone', AES::encryptString($account))
                    ->first();
    }

    public function findByConditions($credentials) : ?Admin
    {
        return $this->query()->where($credentials)->first();
    }


}
