<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 业务线枚举
 */
enum BizEnum: string
{
    use EnumsHelper;

    case USER = 'user';
    case MERCHANT = 'merchant';
    case ADMIN = 'admin';
    case SYSTEM = 'system';

    public static function labels(): array
    {
        return [
            self::USER->value => '用户端',
            self::MERCHANT->value => '商家端',
            self::ADMIN->value => '管理端',
            self::SYSTEM->value => '系统端',
        ];
    }

    public static function colors(): array
    {
        return [
            self::USER->value => 'primary',
            self::MERCHANT->value => 'success',
            self::ADMIN->value => 'warning',
            self::SYSTEM->value => 'info',
        ];
    }

    public static function icons(): array
    {
        return [
            self::USER->value => 'heroicon-o-user',
            self::MERCHANT->value => 'heroicon-o-building-storefront',
            self::ADMIN->value => 'heroicon-o-cog',
            self::SYSTEM->value => 'heroicon-o-computer-desktop',
        ];
    }
}
