<?php

namespace RedJasmine\Admin\Domain\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;


interface AdminReadRepositoryInterface extends ReadRepositoryInterface
{


    public function findByName(string $name) : ?Admin;


    public function findByEmail(string $email) : ?Admin;

    public function findByPhone(string $phone) : ?Admin;

    /**
     * 登录账号信息
     *
     * @param  string  $account
     *
     * @return User|null
     */
    public function findByAccount(string $account) : ?Admin;


    public function findByConditions($credentials) : ?Admin;
}
