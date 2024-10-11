<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum OrderQuantityLimitTypeEnum: string
{

    use EnumsHelper;

    // 不限制
    case UNLIMITED = 'unlimited';

    // 终身限制
    case LIFETIME = 'lifetime';

    // 单次限购
    case ONCE = 'once';

    // 按天限制
    case DAY = 'day';

    // 按月限制
    case MONTH = 'month';
    // 按周限制
    case WEEK = 'week';
    // 按年限制
    case YEAR = 'year';


    public static function labels() : array
    {
        return [
            self::UNLIMITED->value => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.unlimited'),
            self::LIFETIME->value  => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.lifetime'),
            self::ONCE->value      => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.once'),
            self::DAY->value       => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.day'),
            self::MONTH->value     => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.month'),
            self::YEAR->value      => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.year'),
            self::WEEK->value      => __('red-jasmine-ecommerce::ecommerce.enums.order_quantity_limit_type.week'),
        ];
    }
}
