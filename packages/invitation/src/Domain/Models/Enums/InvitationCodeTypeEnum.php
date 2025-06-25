<?php

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 邀请码类型枚举
 */
enum InvitationCodeTypeEnum: string
{
    use EnumsHelper;

    case SYSTEM = 'system';
    case CUSTOM = 'custom';

    /**
     * 获取枚举标签映射
     */
    public static function labels(): array
    {
        return [
            self::SYSTEM->value => '系统生成',
            self::CUSTOM->value => '自定义',
        ];
    }

    /**
     * 获取枚举颜色映射
     */
    public static function colors(): array
    {
        return [
            self::SYSTEM->value => 'info',
            self::CUSTOM->value => 'primary',
        ];
    }

    /**
     * 获取枚举图标映射
     */
    public static function icons(): array
    {
        return [
            self::SYSTEM->value => 'heroicon-o-cog-6-tooth',
            self::CUSTOM->value => 'heroicon-o-pencil-square',
        ];
    }
} 