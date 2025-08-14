<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 消息类型枚举
 */
enum MessageTypeEnum: string
{
    use EnumsHelper;

    case NOTIFICATION = 'notification';
    case ALERT = 'alert';
    case REMINDER = 'reminder';

    public static function labels(): array
    {
        return [
            self::NOTIFICATION->value => '通知',
            self::ALERT->value => '警告',
            self::REMINDER->value => '提醒',
        ];
    }

    public static function colors(): array
    {
        return [
            self::NOTIFICATION->value => 'info',
            self::ALERT->value => 'danger',
            self::REMINDER->value => 'warning',
        ];
    }

    public static function icons(): array
    {
        return [
            self::NOTIFICATION->value => 'heroicon-o-bell',
            self::ALERT->value => 'heroicon-o-exclamation-triangle',
            self::REMINDER->value => 'heroicon-o-clock',
        ];
    }
}
