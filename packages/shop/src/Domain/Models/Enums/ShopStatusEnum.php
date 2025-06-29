<?php

namespace RedJasmine\Shop\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ShopStatusEnum: string
{
    use EnumsHelper;

    case PENDING = 'pending';
    case ACTIVATED = 'activated';
    case SUSPENDED = 'suspended';
    case CANCELED = 'canceled';

    public static function labels(): array
    {
        return [
            self::PENDING->value => '待审核',
            self::ACTIVATED->value => '已激活',
            self::SUSPENDED->value => '已暂停',
            self::CANCELED->value => '已注销',
        ];
    }

    public static function colors(): array
    {
        return [
            self::PENDING->value => 'warning',
            self::ACTIVATED->value => 'success',
            self::SUSPENDED->value => 'danger',
            self::CANCELED->value => 'secondary',
        ];
    }

    public static function icons(): array
    {
        return [
            self::PENDING->value => 'heroicon-o-clock',
            self::ACTIVATED->value => 'heroicon-o-check-circle',
            self::SUSPENDED->value => 'heroicon-o-pause-circle',
            self::CANCELED->value => 'heroicon-o-x-circle',
        ];
    }
} 