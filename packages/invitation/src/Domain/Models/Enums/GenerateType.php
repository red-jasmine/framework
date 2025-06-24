<?php

declare(strict_types=1);

namespace RedJasmine\Invitation\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 邀请码生成类型枚举
 */
enum GenerateType: string
{
    use EnumsHelper;
    
    case CUSTOM = 'custom';    // 自定义
    case SYSTEM = 'system';    // 系统生成

    /**
     * 获取枚举标签
     */
    public function labels(): array
    {
        return [
            'custom' => '自定义',
            'system' => '系统生成',
        ];
    }

    /**
     * 获取枚举图标
     */
    public function icons(): array
    {
        return [
            'custom' => 'heroicon-o-pencil-square',
            'system' => 'heroicon-o-cog-6-tooth',
        ];
    }

    /**
     * 获取枚举颜色
     */
    public function colors(): array
    {
        return [
            'custom' => 'primary',
            'system' => 'success',
        ];
    }
} 