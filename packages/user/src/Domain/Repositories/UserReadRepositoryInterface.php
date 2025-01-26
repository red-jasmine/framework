<?php

namespace RedJasmine\User\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\User\Domain\Models\User;

interface UserReadRepositoryInterface extends ReadRepositoryInterface
{
    public function findByName(string $name) : ?User;

    /**
     * 登录账号信息
     *
     * @param  string  $account
     *
     * @return User|null
     */
    public function findByAccount(string $account) : ?User;
}
