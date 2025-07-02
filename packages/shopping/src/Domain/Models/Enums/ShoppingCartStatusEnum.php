<?php

namespace RedJasmine\Shopping\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ShoppingCartStatusEnum: string
{
    use EnumsHelper;

    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CONVERTED = 'converted';
    case CLEARED = 'cleared';

    public static function labels() : array
    {
        return [
            self::ACTIVE->value    => __('red-jasmine-shopping-cart::shopping-cart.enums.status.active'),
            self::EXPIRED->value   => __('red-jasmine-shopping-cart::shopping-cart.enums.status.expired'),
            self::CONVERTED->value => __('red-jasmine-shopping-cart::shopping-cart.enums.status.converted'),
            self::CLEARED->value   => __('red-jasmine-shopping-cart::shopping-cart.enums.status.cleared'),
        ];
    }

    public static function colors() : array
    {
        return [
            self::ACTIVE->value    => 'success',
            self::EXPIRED->value   => 'warning',
            self::CONVERTED->value => 'info',
            self::CLEARED->value   => 'danger',
        ];
    }

    public static function icons() : array
    {
        return [
            self::ACTIVE->value    => 'heroicon-o-shopping-cart',
            self::EXPIRED->value   => 'heroicon-o-clock',
            self::CONVERTED->value => 'heroicon-o-check-circle',
            self::CLEARED->value   => 'heroicon-o-trash',
        ];
    }
} 