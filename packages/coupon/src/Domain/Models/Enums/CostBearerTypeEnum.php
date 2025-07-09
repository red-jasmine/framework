<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CostBearerTypeEnum: string
{
    use EnumsHelper;

    case PLATFORM = 'platform';
    case MERCHANT = 'merchant';
    case BROADCASTER = 'broadcaster';

    public static function labels(): array
    {
        return [
            self::PLATFORM->value => __('red-jasmine-coupon::cost_bearer.enums.type.platform'),
            self::MERCHANT->value => __('red-jasmine-coupon::cost_bearer.enums.type.merchant'),
            self::BROADCASTER->value => __('red-jasmine-coupon::cost_bearer.enums.type.broadcaster'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::PLATFORM->value => 'primary',
            self::MERCHANT->value => 'secondary',
            self::BROADCASTER->value => 'info',
        ];
    }

    public static function icons(): array
    {
        return [
            self::PLATFORM->value => 'heroicon-o-building-office',
            self::MERCHANT->value => 'heroicon-o-building-storefront',
            self::BROADCASTER->value => 'heroicon-o-video-camera',
        ];
    }
} 