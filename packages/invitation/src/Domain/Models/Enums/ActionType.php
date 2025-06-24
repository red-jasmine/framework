<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 动作类型枚举
 */
enum ActionType: string
{
    use EnumsHelper;
    
    case CLICK = 'click';              // 点击
    case SCAN = 'scan';                // 扫码
    case SHARE = 'share';              // 分享
    case DOWNLOAD = 'download';        // 下载
    case REGISTRATION = 'registration'; // 注册
    case PURCHASE = 'purchase';        // 购买

    /**
     * 获取枚举标签
     */
    public function labels(): array
    {
        return [
            'click' => '点击',
            'scan' => '扫码',
            'share' => '分享',
            'download' => '下载',
            'registration' => '注册',
            'purchase' => '购买',
        ];
    }

    /**
     * 获取枚举图标
     */
    public function icons(): array
    {
        return [
            'click' => 'heroicon-o-cursor-arrow-rays',
            'scan' => 'heroicon-o-qr-code',
            'share' => 'heroicon-o-share',
            'download' => 'heroicon-o-arrow-down-tray',
            'registration' => 'heroicon-o-user-plus',
            'purchase' => 'heroicon-o-shopping-cart',
        ];
    }

    /**
     * 获取枚举颜色
     */
    public function colors(): array
    {
        return [
            'click' => 'primary',
            'scan' => 'info',
            'share' => 'success',
            'download' => 'warning',
            'registration' => 'secondary',
            'purchase' => 'danger',
        ];
    }
} 