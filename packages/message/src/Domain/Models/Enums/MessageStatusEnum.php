<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 消息状态枚举
 */
enum MessageStatusEnum: string
{
    use EnumsHelper;

    case UNREAD = 'unread';
    case READ = 'read';
    case ARCHIVED = 'archived';

    public static function labels(): array
    {
        return [
            self::UNREAD->value => '未读',
            self::READ->value => '已读',
            self::ARCHIVED->value => '已归档',
        ];
    }

    public static function colors(): array
    {
        return [
            self::UNREAD->value => 'warning',
            self::READ->value => 'success',
            self::ARCHIVED->value => 'gray',
        ];
    }

    public static function icons(): array
    {
        return [
            self::UNREAD->value => 'heroicon-o-envelope',
            self::READ->value => 'heroicon-o-envelope-open',
            self::ARCHIVED->value => 'heroicon-o-archive-box',
        ];
    }
}
