<?php

namespace RedJasmine\Invitation\UI\Http\Admin\Api\Controllers;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\UserData;
use Illuminate\Routing\Controller as BaseController;

/**
 * 管理员API基础控制器
 */
abstract class Controller extends BaseController
{
    /**
     * 获取当前用户
     */
    protected function getOwner(): ?UserInterface
    {
        // 这里应该从认证中获取当前管理员用户
        // 暂时返回null，实际项目中需要实现
        return null;
    }

    /**
     * 获取所有者键名
     */
    protected function getOwnerKey(): string
    {
        return 'owner';
    }
} 