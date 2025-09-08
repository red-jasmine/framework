<?php

namespace RedJasmine\Admin\Domain\Repositories;

use RedJasmine\Admin\Domain\Models\Admin;
use RedJasmine\User\Domain\Repositories\UserRepositoryInterface;

/**
 * 管理员仓库接口
 *
 * 提供管理员数据的读写操作统一接口
 *
 * @method Admin find($id)
 */
interface AdminRepositoryInterface extends UserRepositoryInterface
{
    /**
     * 根据用户名查找管理员
     *
     * @param string $name 用户名
     * @return Admin|null
     */
    public function findByName(string $name) : ?Admin;

    /**
     * 根据邮箱查找管理员
     *
     * @param string $email 邮箱地址
     * @return Admin|null
     */
    public function findByEmail(string $email) : ?Admin;

    /**
     * 根据手机号查找管理员
     *
     * @param string $phone 手机号码
     * @return Admin|null
     */
    public function findByPhone(string $phone) : ?Admin;

    /**
     * 根据登录账号信息查找管理员
     *
     * @param string $account 登录账号（可以是用户名、邮箱或手机号）
     * @return Admin|null
     */
    public function findByAccount(string $account) : ?Admin;

    /**
     * 根据认证凭据查找管理员
     *
     * @param mixed $credentials 认证凭据
     * @return Admin|null
     */
    public function findByConditions($credentials) : ?Admin;
}
