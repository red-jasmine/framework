<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 推送状态枚举
 */
enum PushStatusEnum: string
{
    use EnumsHelper;

    case PENDING = 'pending';
    case SENT = 'sent';
    case FAILED = 'failed';

    public static function labels(): array
    {
        return [
            self::PENDING->value => '等待中',
            self::SENT->value => '已发送',
            self::FAILED->value => '发送失败',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING->value => 'warning',
            self::SENT->value => 'success',
            self::FAILED->value => 'danger',
        ];
    }

    public static function icons(): array
    {
        return [
            self::PENDING->value => 'heroicon-o-clock',
            self::SENT->value => 'heroicon-o-check-circle',
            self::FAILED->value => 'heroicon-o-x-circle',
        ];
    }

    /**
     * 是否为最终状态
     */
    public function isFinalStatus(): bool
    {
        return match ($this) {
            self::SENT, self::FAILED => true,
            self::PENDING => false,
        };
    }

    /**
     * 是否可以重试
     */
    public function canRetry(): bool
    {
        return $this === self::FAILED;
    }
}
