<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CouponTypeEnum: string
{
    use EnumsHelper;

    case  SHOP = 'shop';
    case  SYSTEM = 'system';

    public static function labels() : array
    {
        return [
            self::SHOP->value   => __('red-jasmine-coupon::coupon.enums.coupon_type.shop'),
            self::SYSTEM->value => __('red-jasmine-coupon::coupon.enums.coupon_type.system'),

        ];
    }

    public static function colors() : array
    {
        return [
            self::SHOP->value   => 'gray',
            self::SYSTEM->value => 'success',
        ];
    }

    public static function icons() : array
    {
        return [
            self::SHOP->value   => 'heroicon-o-check-circle',
            self::SYSTEM->value => 'heroicon-o-x-circle',
        ];
    }
}
