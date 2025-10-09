<?php

namespace RedJasmine\UserCore\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use RedJasmine\UserCore\Domain\Models\User;

/**
 * @method User  find($id)
 */
interface BaseUserRepositoryInterface extends RepositoryInterface
{
    public function findByName(string $name) : ?User;

    public function findByEmail(string $email) : ?User;

    public function findByPhone(string $phone) : ?User;

    /**
     * 登录账号信息
     *
     * @param  string  $account
     *
     * @return User|null
     */
    public function findByAccount(string $account) : ?User;

    public function findByConditions($credentials) : ?User;
}
