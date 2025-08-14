<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 通用状态枚举
 */
enum StatusEnum: string
{
    use EnumsHelper;

    case ENABLE = 'enable';
    case DISABLE = 'disable';

    public static function labels(): array
    {
        return [
            self::ENABLE->value => '启用',
            self::DISABLE->value => '禁用',
        ];
    }

    public static function colors(): array
    {
        return [
            self::ENABLE->value => 'success',
            self::DISABLE->value => 'gray',
        ];
    }

    public static function icons(): array
    {
        return [
            self::ENABLE->value => 'heroicon-o-check-circle',
            self::DISABLE->value => 'heroicon-o-x-circle',
        ];
    }

    /**
     * 是否启用
     */
    public function isEnabled(): bool
    {
        return $this === self::ENABLE;
    }

    /**
     * 是否禁用
     */
    public function isDisabled(): bool
    {
        return $this === self::DISABLE;
    }
}
