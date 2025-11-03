<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

use Illuminate\Support\Arr;
use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ProductStatusEnum: string
{
    use EnumsHelper;


    // 审批中
    case DRAFT = 'draft'; // 未发布
    case PENDING = 'pending'; // 审批中

    case AVAILABLE = 'available'; // 在售
    case PAUSED = 'paused'; // 暂停销售
    case UNAVAILABLE = 'unavailable'; // 下架
    case FORBIDDEN = 'forbidden'; // 禁售



    case ARCHIVED = 'archived'; // 已归档
    case DELETED = 'deleted'; // 删除


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
            self::DRAFT->value       => __('red-jasmine-product::product.enums.status.draft'),
            self::PENDING->value     => __('red-jasmine-product::product.enums.status.pending'),
            self::AVAILABLE->value   => __('red-jasmine-product::product.enums.status.available'),
            self::PAUSED->value      => __('red-jasmine-product::product.enums.status.paused'),
            self::UNAVAILABLE->value => __('red-jasmine-product::product.enums.status.unavailable'),
            self::FORBIDDEN->value   => __('red-jasmine-product::product.enums.status.forbidden'),
            self::ARCHIVED->value    => __('red-jasmine-product::product.enums.status.archived'),
            self::DELETED->value     => __('red-jasmine-product::product.enums.status.deleted'),
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
            self::PAUSED->value, // 暂停销售状态
            self::UNAVAILABLE->value, // 下架状态

        ];
    }

    public static function variantStatus() : array
    {
        return [
            self::AVAILABLE->value => __('red-jasmine-product::product.enums.status.available'),
            self::PAUSED->value      => __('red-jasmine-product::product.enums.status.paused'),
        ];
    }

    public static function colors() : array
    {

        return [
            self::DRAFT->value       => 'primary',
            self::PENDING->value     => 'info',
            self::AVAILABLE->value   => 'success',
            self::PAUSED->value      => 'warning',
            self::UNAVAILABLE->value => 'gray',
            self::FORBIDDEN->value   => 'danger',
            self::ARCHIVED->value    => 'gray',
            self::DELETED->value     => 'gray',
        ];
    }

    //danger、gray、info、primary、success 或 warning

    public static function icons() : array
    {
        return [
            self::DRAFT->value       => 'heroicon-o-document',
            self::PENDING->value     => 'heroicon-o-clock',
            self::AVAILABLE->value   => 'heroicon-o-shopping-bag',
            self::PAUSED->value      => 'heroicon-o-pause-circle',
            self::UNAVAILABLE->value => 'heroicon-o-archive-box',
            self::FORBIDDEN->value   => 'heroicon-o-no-symbol',
            self::ARCHIVED->value    => 'heroicon-o-archive-box-arrow-down',
            self::DELETED->value     => 'heroicon-o-trash',
        ];
    }

    public function updatingAllowed() : array
    {
        return match ($this) {
            self::DRAFT => Arr::only(self::labels(), [
                self::DRAFT->value,
                self::PENDING->value,
                self::AVAILABLE->value,
            ]),
            self::PENDING => Arr::only(self::labels(), [
                self::PENDING->value,
                self::AVAILABLE->value,
                self::DRAFT->value,
            ]),
            self::AVAILABLE => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::PAUSED->value,
                self::UNAVAILABLE->value,
            ]),
            self::PAUSED => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::PAUSED->value,
                self::UNAVAILABLE->value,
            ]),
            self::UNAVAILABLE => Arr::only(self::labels(), [
                self::AVAILABLE->value,
                self::UNAVAILABLE->value,
                self::ARCHIVED->value,
            ]),
            self::FORBIDDEN => Arr::only(self::labels(), [
                self::FORBIDDEN->value,
            ]),
            self::ARCHIVED => Arr::only(self::labels(), [
                self::ARCHIVED->value,
            ]),
            self::DELETED => [],
        };
    }
}
