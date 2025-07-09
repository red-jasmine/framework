<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum IssueStrategyEnum: string
{
    use EnumsHelper;

    case AUTO = 'auto';
    case MANUAL = 'manual';
    case CODE = 'code';

    public static function labels(): array
    {
        return [
            self::AUTO->value => __('red-jasmine-coupon::issue.enums.strategy.auto'),
            self::MANUAL->value => __('red-jasmine-coupon::issue.enums.strategy.manual'),
            self::CODE->value => __('red-jasmine-coupon::issue.enums.strategy.code'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::AUTO->value => 'success',
            self::MANUAL->value => 'warning',
            self::CODE->value => 'info',
        ];
    }

    public static function icons(): array
    {
        return [
            self::AUTO->value => 'heroicon-o-bolt',
            self::MANUAL->value => 'heroicon-o-hand-raised',
            self::CODE->value => 'heroicon-o-qr-code',
        ];
    }
} 