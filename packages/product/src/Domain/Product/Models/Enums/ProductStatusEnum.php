<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use Illuminate\Support\Arr;
use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatusEnum: string
{
    use EnumsHelper;


    case AVAILABLE = 'available'; // 在售

    case SOLD_OUT = 'sold_out'; // 售罄

    case STOP_SALE = 'stop_sale'; // 停售

    case FORBIDDEN = 'forbidden'; // 禁售

    // 审批中
    case DRAFT = 'draft'; // 未发布
    case PENDING = 'pending'; // 审批中


    case ARCHIVED = 'archived'; // 已归档
    case DELETED = 'deleted'; // 删除 仅在 sku 中使用


    public static function creatingAllowed() : array
    {
        return Arr::only(self::labels(), [
            self::AVAILABLE->value,
            self::DRAFT->value,
        ]);
    }

    public static function labels() : array
    {
        return [
            self::AVAILABLE->value => __('red-jasmine-product::product.enums.status.on_sale'),
            self::SOLD_OUT->value  => __('red-jasmine-product::product.enums.status.sold_out'),
            self::STOP_SALE->value => __('red-jasmine-product::product.enums.status.stop_sale'),
            self::FORBIDDEN->value => __('red-jasmine-product::product.enums.status.forbid_sale'),
            self::DRAFT->value     => __('red-jasmine-product::product.enums.status.draft'),
        ];

    }

    public static function isAllowTimingSaleStatus($status) : bool
    {
        $status = self::from($status);
        return in_array($status->value, self::allowTimingSaleStatus(), true);
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
            self::STOP_SALE->value, // 下架状态
            self::SOLD_OUT->value, // 售罄状态
        ];
    }

    public static function variantStatus() : array
    {
        return [
            self::AVAILABLE->value => __('red-jasmine-product::product.enums.status.on_sale'),
            self::SOLD_OUT->value  => __('red-jasmine-product::product.enums.status.sold_out'),
        ];
    }

    public static function colors() : array
    {

        return [
            self::AVAILABLE->value => 'success',
            self::SOLD_OUT->value  => 'warning',
            self::STOP_SALE->value => 'danger',
            self::FORBIDDEN->value => 'danger',
            self::DRAFT->value     => 'primary',
        ];
    }

    //danger、gray、info、primary、success 或 warning

    public static function icons() : array
    {
        return [
            self::AVAILABLE->value => 'heroicon-o-shopping-bag',
            self::SOLD_OUT->value  => 'heroicon-o-bookmark-slash',
            self::STOP_SALE->value => 'heroicon-o-archive-box-x-mark',
            self::FORBIDDEN->value => 'heroicon-o-no-symbol',
            self::DRAFT->value     => 'heroicon-o-document',
        ];
    }

    public function updatingAllowed() : array
    {
        return match ($this) {
            self::AVAILABLE => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::SOLD_OUT->value,
                self::STOP_SALE->value,
                //                self::FORBID_SALE->value,
                //                self::DRAFT->value,
            ]),
            self::SOLD_OUT => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::SOLD_OUT->value,
                self::STOP_SALE->value,
                //                self::DRAFT->value,
            ]),
            self::STOP_SALE => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::SOLD_OUT->value,
                self::STOP_SALE->value,

            ]),
            self::FORBIDDEN => Arr::only(self::labels(), [
                self::FORBIDDEN->value,
            ]),
            self::DRAFT => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::DRAFT->value,
            ]),
            self::DELETED => [],

        };
        return [];
    }
}
