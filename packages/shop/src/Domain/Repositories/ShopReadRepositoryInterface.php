<?php

namespace RedJasmine\Shop\Domain\Repositories;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\User\Domain\Repositories\UserReadRepositoryInterface;

interface ShopReadRepositoryInterface extends UserReadRepositoryInterface
{
    public function findByName(string $name): ?Shop;

    public function findByEmail(string $email): ?Shop;

    public function findByPhone(string $phone): ?Shop;

    /**
     * 登录账号信息
     *
     * @param string $account
     *
     * @return Shop|null
     */
    public function findByAccount(string $account): ?Shop;

    public function findByConditions($credentials): ?Shop;
} 