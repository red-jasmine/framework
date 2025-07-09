<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum UserCouponStatusEnum: string
{
    use EnumsHelper;

    case AVAILABLE = 'available';
    case USED = 'used';
    case EXPIRED = 'expired';

    public static function labels(): array
    {
        return [
            self::AVAILABLE->value => __('red-jasmine-coupon::user_coupon.enums.status.available'),
            self::USED->value => __('red-jasmine-coupon::user_coupon.enums.status.used'),
            self::EXPIRED->value => __('red-jasmine-coupon::user_coupon.enums.status.expired'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::AVAILABLE->value => 'success',
            self::USED->value => 'info',
            self::EXPIRED->value => 'danger',
        ];
    }

    public static function icons(): array
    {
        return [
            self::AVAILABLE->value => 'heroicon-o-check-circle',
            self::USED->value => 'heroicon-o-check-badge',
            self::EXPIRED->value => 'heroicon-o-x-circle',
        ];
    }
} 