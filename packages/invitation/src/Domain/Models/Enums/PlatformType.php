<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 平台类型枚举
 */
enum PlatformType: string
{
    use EnumsHelper;
    
    case WEB = 'web';                // Web网站
    case H5 = 'h5';                  // H5页面
    case MINI_PROGRAM = 'mini_program'; // 小程序
    case APP = 'app';                // 手机应用
    case WECHAT = 'wechat';          // 微信
    case ALIPAY = 'alipay';          // 支付宝

    /**
     * 获取枚举标签
     */
    public function labels(): array
    {
        return [
            'web' => 'Web网站',
            'h5' => 'H5页面',
            'mini_program' => '小程序',
            'app' => '手机应用',
            'wechat' => '微信',
            'alipay' => '支付宝',
        ];
    }

    /**
     * 获取枚举图标
     */
    public function icons(): array
    {
        return [
            'web' => 'heroicon-o-globe-alt',
            'h5' => 'heroicon-o-device-phone-mobile',
            'mini_program' => 'heroicon-o-squares-2x2',
            'app' => 'heroicon-o-device-phone-mobile',
            'wechat' => 'heroicon-o-chat-bubble-left-ellipsis',
            'alipay' => 'heroicon-o-credit-card',
        ];
    }

    /**
     * 获取枚举颜色
     */
    public function colors(): array
    {
        return [
            'web' => 'primary',
            'h5' => 'info',
            'mini_program' => 'warning',
            'app' => 'success',
            'wechat' => 'success',
            'alipay' => 'primary',
        ];
    }
} 