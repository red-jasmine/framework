<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RuleTypeEnum: string
{
    use EnumsHelper;

    case INCLUDE = 'include';
    case EXCLUDE = 'exclude';

    public static function labels(): array
    {
        return [
            self::INCLUDE->value => __('red-jasmine-coupon::rule.enums.type.include'),
            self::EXCLUDE->value => __('red-jasmine-coupon::rule.enums.type.exclude'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::INCLUDE->value => 'success',
            self::EXCLUDE->value => 'danger',
        ];
    }

    public static function icons(): array
    {
        return [
            self::INCLUDE->value => 'heroicon-o-check-circle',
            self::EXCLUDE->value => 'heroicon-o-x-circle',
        ];
    }
} 