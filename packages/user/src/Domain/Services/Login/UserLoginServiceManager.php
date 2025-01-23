<?php

namespace RedJasmine\User\Domain\Services\Login;

use RedJasmine\User\Domain\Models\User;

/**
 * 用户登陆管理器
 */
class UserLoginServiceManager
{
    // 短信登陆
    // 密码登陆
    // 社交账号登陆
    // ... 自定义扩展

    protected array $config;

    // 登陆驱动
    public function __construct()
    {
    }

    /**
     * 创建 服务
     * @return void
     */
    public function create()
    {

    }


    public function login(User $user)
    {
        // JWT
        // 非对称加密算法 签名
        // 客户端验证 驱动
        // 主要符合用户登陆核心功能

    }

}
