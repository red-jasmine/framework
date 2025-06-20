<?php

namespace RedJasmine\Admin\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Admin\Domain\Repositories\AdminReadRepositoryInterface;
use RedJasmine\User\Infrastructure\ReadRepositories\Mysql\UserReadRepository;

class AdminReadRepository extends UserReadRepository implements AdminReadRepositoryInterface
{

    public static string $modelClass = Admin::class;

    public function findByName(string $name) : ?Admin
    {
        return parent::findByName($name); 
    }

    public function findByEmail(string $email) : ?Admin
    {
        return parent::findByEmail($email); 
    }

    public function findByPhone(string $phone) : ?Admin
    {
        return parent::findByPhone($phone); 
    }

    public function findByAccount(string $account) : ?Admin
    {
        return parent::findByAccount($account); 
    }

    public function findByConditions($credentials) : ?Admin
    {
        return parent::findByConditions($credentials); 
    }


}
