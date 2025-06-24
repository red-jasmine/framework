<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 邀请去向类型枚举
 */
enum DestinationType: string
{
    use EnumsHelper;
    
    case URL = 'url';                    // 链接
    case ROUTE = 'route';                // 路由
    case DOWNLOAD = 'download';          // 下载
    case REGISTRATION = 'registration';  // 注册
    case LOGIN = 'login';                // 登录
    case PRODUCT = 'product';            // 产品
    case ACTIVITY = 'activity';          // 活动

    /**
     * 获取枚举标签
     */
    public function labels(): array
    {
        return [
            'url' => '链接',
            'route' => '路由',
            'download' => '下载',
            'registration' => '注册',
            'login' => '登录',
            'product' => '产品',
            'activity' => '活动',
        ];
    }

    /**
     * 获取枚举图标
     */
    public function icons(): array
    {
        return [
            'url' => 'heroicon-o-link',
            'route' => 'heroicon-o-map-pin',
            'download' => 'heroicon-o-arrow-down-tray',
            'registration' => 'heroicon-o-user-plus',
            'login' => 'heroicon-o-arrow-right-on-rectangle',
            'product' => 'heroicon-o-cube',
            'activity' => 'heroicon-o-calendar-days',
        ];
    }

    /**
     * 获取枚举颜色
     */
    public function colors(): array
    {
        return [
            'url' => 'primary',
            'route' => 'info',
            'download' => 'success',
            'registration' => 'warning',
            'login' => 'primary',
            'product' => 'secondary',
            'activity' => 'danger',
        ];
    }
} 