<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 邀请码状态枚举
 */
enum CodeStatus: string
{
    use EnumsHelper;
    
    case ACTIVE = 'active';      // 有效
    case DISABLED = 'disabled';  // 禁用
    case EXPIRED = 'expired';    // 过期

    /**
     * 是否为可用状态
     */
    public function isUsable(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * 是否可以转换到目标状态
     */
    public function canTransitionTo(CodeStatus $target): bool
    {
        return match ($this) {
            self::ACTIVE => in_array($target, [self::DISABLED, self::EXPIRED]),
            self::DISABLED => $target === self::ACTIVE,
            self::EXPIRED => $target === self::ACTIVE, // 支持延期重新激活
        };
    }

    /**
     * 获取枚举标签
     */
    public function labels(): array
    {
        return [
            'active' => '有效',
            'disabled' => '禁用',
            'expired' => '过期',
        ];
    }

    /**
     * 获取枚举图标
     */
    public function icons(): array
    {
        return [
            'active' => 'heroicon-o-check-circle',
            'disabled' => 'heroicon-o-x-circle',
            'expired' => 'heroicon-o-clock',
        ];
    }

    /**
     * 获取枚举颜色
     */
    public function colors(): array
    {
        return [
            'active' => 'success',
            'disabled' => 'warning',
            'expired' => 'danger',
        ];
    }
} 