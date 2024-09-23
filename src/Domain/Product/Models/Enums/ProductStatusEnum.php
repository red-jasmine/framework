<?php

namespace RedJasmine\Product\Domain\Product\Models\Enums;

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
            self::ON_SALE->value     => '在售',
            self::SOLD_OUT->value    => '停售',
            self::OFF_SHELF->value   => '下架',
            self::PRE_SALE->value    => '预售',
            self::FORBID_SALE->value => '禁售',
            self::DRAFT->value       => '草稿',
        ];

    }

    public static function skusStatus() : array
    {
        return [
            self::ON_SALE->value  => '在售',
            self::SOLD_OUT->value => '售停',
        ];
    }

    //danger、gray、info、primary、success 或 warning
    public static function colors() : array
    {
        return [
            self::ON_SALE->value     => 'success',
            self::SOLD_OUT->value    => 'warning',
            self::OFF_SHELF->value   => 'warning',
            self::PRE_SALE->value    => 'primary',
            self::FORBID_SALE->value => 'danger',
            self::DRAFT->value       => 'gray',
        ];
    }

}
