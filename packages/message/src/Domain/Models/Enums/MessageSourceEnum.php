<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 消息来源枚举
 */
enum MessageSourceEnum: string
{
    use EnumsHelper;

    case SYSTEM = 'system';
    case USER = 'user';
    case API = 'api';

    public static function labels(): array
    {
        return [
            self::SYSTEM->value => '系统',
            self::USER->value => '用户',
            self::API->value => 'API',
        ];
    }

    public static function colors(): array
    {
        return [
            self::SYSTEM->value => 'info',
            self::USER->value => 'primary',
            self::API->value => 'success',
        ];
    }

    public static function icons(): array
    {
        return [
            self::SYSTEM->value => 'heroicon-o-computer-desktop',
            self::USER->value => 'heroicon-o-user',
            self::API->value => 'heroicon-o-code-bracket',
        ];
    }
}
