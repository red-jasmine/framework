<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum CouponStatusEnum: string
{
    use EnumsHelper;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case PAUSED = 'paused';
    case EXPIRED = 'expired';

    public static function labels(): array
    {
        return [
            self::DRAFT->value => __('red-jasmine-coupon::coupon.enums.status.draft'),
            self::PUBLISHED->value => __('red-jasmine-coupon::coupon.enums.status.published'),
            self::PAUSED->value => __('red-jasmine-coupon::coupon.enums.status.paused'),
            self::EXPIRED->value => __('red-jasmine-coupon::coupon.enums.status.expired'),
        ];
    }

    public static function colors(): array
    {
        return [
            self::DRAFT->value => 'gray',
            self::PUBLISHED->value => 'success',
            self::PAUSED->value => 'warning',
            self::EXPIRED->value => 'danger',
        ];
    }

    public static function icons(): array
    {
        return [
            self::DRAFT->value => 'heroicon-o-document-text',
            self::PUBLISHED->value => 'heroicon-o-check-circle',
            self::PAUSED->value => 'heroicon-o-pause-circle',
            self::EXPIRED->value => 'heroicon-o-x-circle',
        ];
    }
} 