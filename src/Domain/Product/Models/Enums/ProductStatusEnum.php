<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use Filament\Support\Colors\Color;
use Illuminate\Support\Arr;
use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatusEnum: string
{
    use EnumsHelper;


    case ON_SALE = 'on_sale'; // 在售

    case SOLD_OUT = 'sold_out'; // 售停

    case OFF_SHELF = 'off_shelf'; // 下架

    case FORBID_SALE = 'forbid_sale'; // 禁售

    case DELETED = 'deleted'; // 删除 仅在 sku 中使用

    case DRAFT = 'draft'; // 草稿


    public static function creatingAllowed() : array
    {
        return Arr::only(self::labels(), [
            self::ON_SALE->value,
            self::DRAFT->value,
        ]);
    }


    public function updatingAllowed() : array
    {
        return match ($this) {
            self::ON_SALE => Arr::only(self::labels(), [
                self::ON_SALE->value,
                self::SOLD_OUT->value,
                self::OFF_SHELF->value,
                //                self::FORBID_SALE->value,
                //                self::DRAFT->value,
            ]),
            self::SOLD_OUT => Arr::only(self::labels(), [
                self::ON_SALE->value,
                self::SOLD_OUT->value,
                self::OFF_SHELF->value,
                //                self::DRAFT->value,
            ]),
            self::OFF_SHELF => Arr::only(self::labels(), [
                self::ON_SALE->value,
                self::SOLD_OUT->value,
                self::OFF_SHELF->value,

            ]),
            self::FORBID_SALE => Arr::only(self::labels(), [
                self::FORBID_SALE->value,
            ]),
            self::DRAFT => Arr::only(self::labels(), [
                self::ON_SALE->value,
                self::DRAFT->value,
            ]),
            self::DELETED => [],

        };
        return [];
    }


    /**
     * 获取允许参加定时上架活动的状态
     *
     * 此方法返回一系列订单状态常量的值，这些状态下的订单可以参与定时上架活动
     * 包括草稿、下架和售罄状态
     *
     * @return array 允许参加定时上架活动的订单状态数组
     */
    public static function allowTimingSaleStatus() : array
    {
        // 返回允许参加定时上架活动的订单状态数组
        return [
            self::DRAFT->value, // 草稿状态
            self::OFF_SHELF->value, // 下架状态
            self::SOLD_OUT->value, // 售罄状态
        ];
    }

    public static function isAllowTimingSaleStatus($status) : bool
    {
        $status = self::from($status);
        return in_array($status->value, self::allowTimingSaleStatus(), true);
    }

    public static function labels() : array
    {
        return [
            self::ON_SALE->value     => __('red-jasmine-product::product.enums.status.on_sale'),
            self::SOLD_OUT->value    => __('red-jasmine-product::product.enums.status.sold_out'),
            self::OFF_SHELF->value   => __('red-jasmine-product::product.enums.status.off_shelf'),
            self::FORBID_SALE->value => __('red-jasmine-product::product.enums.status.forbid_sale'),
            self::DRAFT->value       => __('red-jasmine-product::product.enums.status.draft'),
        ];

    }

    public static function skusStatus() : array
    {
        return [
            self::ON_SALE->value  => __('red-jasmine-product::product.enums.status.on_sale'),
            self::SOLD_OUT->value => __('red-jasmine-product::product.enums.status.sold_out'),
        ];
    }

    //danger、gray、info、primary、success 或 warning
    public static function colors() : array
    {

        return [
            self::ON_SALE->value     => 'success',
            self::SOLD_OUT->value    => 'warning',
            self::OFF_SHELF->value   => 'danger',
            self::FORBID_SALE->value => 'danger',
            self::DRAFT->value       => 'primary',
        ];
    }


    public static function icons() : array
    {
        return [
            self::ON_SALE->value   => 'heroicon-o-shopping-bag',
            self::SOLD_OUT->value  => 'heroicon-o-bookmark-slash',
            self::OFF_SHELF->value => 'heroicon-o-archive-box-x-mark',

            self::FORBID_SALE->value => 'heroicon-o-no-symbol',
            self::DRAFT->value       => 'heroicon-o-document',
        ];
    }
}
