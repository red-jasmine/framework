<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ValidityTypeEnum: string
{
    use EnumsHelper;

    case ABSOLUTE = 'absolute';
    case RELATIVE = 'relative';

    public static function labels(): array
    {
        return [
            self::ABSOLUTE->value => __('red-jasmine-coupon::coupon.enums.validity_type.absolute'),
            self::RELATIVE->value => __('red-jasmine-coupon::coupon.enums.validity_type.relative'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::ABSOLUTE->value => 'primary',
            self::RELATIVE->value => 'secondary',
        ];
    }

    public static function icons(): array
    {
        return [
            self::ABSOLUTE->value => 'heroicon-o-calendar-days',
            self::RELATIVE->value => 'heroicon-o-clock',
        ];
    }
} 