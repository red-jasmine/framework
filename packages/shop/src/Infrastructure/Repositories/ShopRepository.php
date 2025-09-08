<?php

namespace RedJasmine\Shop\Infrastructure\Repositories;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\Shop\Domain\Repositories\ShopRepositoryInterface;
use RedJasmine\User\Infrastructure\Repositories\UserRepository;

/**
 * 店铺仓库实现
 *
 * 提供店铺数据的读写操作统一实现
 */
class ShopRepository extends UserRepository implements ShopRepositoryInterface
{
    protected static string $modelClass = Shop::class;

    /**
     * 根据店铺名称查找店铺
     */
    public function findByName(string $name): ?Shop
    {
        return parent::findByName($name);
    }

    /**
     * 根据邮箱查找店铺
     */
    public function findByEmail(string $email): ?Shop
    {
        return parent::findByEmail($email);
    }

    /**
     * 根据手机号查找店铺
     */
    public function findByPhone(string $phone): ?Shop
    {
        return parent::findByPhone($phone);
    }

    /**
     * 根据登录账号查找店铺
     */
    public function findByAccount(string $account): ?Shop
    {
        return parent::findByAccount($account);
    }

    /**
     * 根据认证凭据查找店铺
     */
    public function findByConditions($credentials): ?Shop
    {
        return parent::findByConditions($credentials);
    }
} 