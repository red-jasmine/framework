<?php

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 邀请码状态枚举
 */
enum InvitationCodeStatusEnum: string
{
    use EnumsHelper;

    case ACTIVE = 'active';
    case DISABLED = 'disabled';
    case EXPIRED = 'expired';
    case EXHAUSTED = 'exhausted';

    /**
     * 获取枚举标签映射
     */
    public static function labels(): array
    {
        return [
            self::ACTIVE->value => '激活',
            self::DISABLED->value => '禁用',
            self::EXPIRED->value => '过期',
            self::EXHAUSTED->value => '用尽',
        ];
    }

    /**
     * 获取枚举颜色映射
     */
    public static function colors(): array
    {
        return [
            self::ACTIVE->value => 'success',
            self::DISABLED->value => 'warning',
            self::EXPIRED->value => 'danger',
            self::EXHAUSTED->value => 'gray',
        ];
    }

    /**
     * 获取枚举图标映射
     */
    public static function icons(): array
    {
        return [
            self::ACTIVE->value => 'heroicon-o-check-circle',
            self::DISABLED->value => 'heroicon-o-pause-circle',
            self::EXPIRED->value => 'heroicon-o-clock',
            self::EXHAUSTED->value => 'heroicon-o-no-symbol',
        ];
    }

    /**
     * 检查状态是否可用
     */
    public function isAvailable(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * 检查是否为最终状态
     */
    public function isFinalStatus(): bool
    {
        return in_array($this, [self::EXPIRED, self::EXHAUSTED]);
    }
} 