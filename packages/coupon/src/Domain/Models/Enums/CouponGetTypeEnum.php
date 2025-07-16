<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CouponGetTypeEnum: string
{
    use EnumsHelper;

    case RECEIVE = 'receive';
    case ISSUE = 'issue';
    case GIVE = 'give';

    public static function labels() : array
    {
        return [
            self::RECEIVE->value => __('red-jasmine-coupon::user_coupon.enums.coupon_get_type.receive'),
            self::ISSUE->value   => __('red-jasmine-coupon::user_coupon.enums.coupon_get_type.issue'),
            self::GIVE->value    => __('red-jasmine-coupon::user_coupon.enums.coupon_get_type.give'),

        ];
    }

    public static function colors() : array
    {
        return [
            self::RECEIVE->value => 'gray',
            self::ISSUE->value   => 'success',
            self::GIVE->value    => 'warning',
        ];
    }

    public static function icons() : array
    {
        return [
            self::RECEIVE->value => 'heroicon-o-check-circle',
            self::ISSUE->value   => 'heroicon-o-x-circle',
            self::GIVE->value    => 'heroicon-o-arrow-right-circle',
        ];
    }
}
