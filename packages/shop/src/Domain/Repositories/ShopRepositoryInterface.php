<?php

namespace RedJasmine\Shop\Domain\Repositories;

use RedJasmine\Shop\Domain\Models\Shop;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;

/**
 * 店铺仓库接口
 *
 * 提供店铺数据的读写操作统一接口
 *
 * @method Shop find($id)
 */
interface ShopRepositoryInterface extends UserRepositoryInterface
{
    /**
     * 根据店铺名称查找店铺
     *
     * @param string $name 店铺名称
     * @return Shop|null
     */
    public function findByName(string $name): ?Shop;

    /**
     * 根据邮箱查找店铺
     *
     * @param string $email 邮箱地址
     * @return Shop|null
     */
    public function findByEmail(string $email): ?Shop;

    /**
     * 根据手机号查找店铺
     *
     * @param string $phone 手机号码
     * @return Shop|null
     */
    public function findByPhone(string $phone): ?Shop;

    /**
     * 根据登录账号信息查找店铺
     *
     * @param string $account 登录账号（可以是用户名、邮箱或手机号）
     * @return Shop|null
     */
    public function findByAccount(string $account): ?Shop;

    /**
     * 根据认证凭据查找店铺
     *
     * @param mixed $credentials 认证凭据
     * @return Shop|null
     */
    public function findByConditions($credentials): ?Shop;
} 