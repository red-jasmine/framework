<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use Filament\Support\Colors\Color;
use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatusEnum: string
{
    use EnumsHelper;


    case DRAFT = 'draft'; // 草稿

    case ON_SALE = 'on_sale'; // 在售

    case PRE_SALE = 'pre_sale'; // 预售

    case SOLD_OUT = 'sold_out'; // 售停

    case OFF_SHELF = 'off_shelf'; // 下架

    case FORBID_SALE = 'forbid_sale'; // 禁售

    case DELETED = 'deleted'; // 删除 仅在 sku 中使用

    public static function labels() : array
    {
        return [
            self::ON_SALE->value     => __('red-jasmine.product::product.enums.status.on_sale'),
            self::SOLD_OUT->value    => __('red-jasmine.product::product.enums.status.sold_out'),
            self::OFF_SHELF->value   => __('red-jasmine.product::product.enums.status.off_shelf'),
            self::PRE_SALE->value    => __('red-jasmine.product::product.enums.status.pre_sale'),
            self::FORBID_SALE->value => __('red-jasmine.product::product.enums.status.forbid_sale'),
            self::DRAFT->value       => __('red-jasmine.product::product.enums.status.draft'),
        ];

    }

    public static function skusStatus() : array
    {
        return [
            self::ON_SALE->value  => __('red-jasmine.product::product.enums.status.on_sale'),
            self::SOLD_OUT->value => __('red-jasmine.product::product.enums.status.sold_out'),
        ];
    }

    //danger、gray、info、primary、success 或 warning
    public static function colors() : array
    {

        return [
            self::ON_SALE->value     => 'success',
            self::SOLD_OUT->value    => 'warning',
            self::OFF_SHELF->value   => 'danger',
            self::PRE_SALE->value    => 'primary',
            self::FORBID_SALE->value => 'danger',
            self::DRAFT->value       => 'info',
        ];
    }


    public static function icons() : array
    {
        return [
            self::ON_SALE->value     => 'heroicon-o-shopping-bag',
            self::SOLD_OUT->value    => 'heroicon-o-bookmark-slash',
            self::OFF_SHELF->value   => 'heroicon-o-archive-box-x-mark',
            self::PRE_SALE->value    => 'heroicon-o-gift-top',
            self::FORBID_SALE->value => 'heroicon-o-no-symbol',
            self::DRAFT->value       => 'heroicon-o-document',
        ];
    }
}
