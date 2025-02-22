<?php

namespace RedJasmine\Vip\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum VipProductStatusEnum: string
{
    use EnumsHelper;

    case ON_SALE = 'on_sale'; // 在售

    case SOLD_OUT = 'sold_out'; // 售罄

    case STOP_SALE = 'stop_sale'; // 停售

    case FORBID_SALE = 'forbid_sale'; // 禁售

    case DELETED = 'deleted'; // 删除 仅在 sku 中使用

    case DRAFT = 'draft'; // 未发布


    public static function labels() : array
    {
        return [
            self::ON_SALE->value     => '在售',
            self::SOLD_OUT->value    => '售罄',
            self::STOP_SALE->value   => '停售',
            self::FORBID_SALE->value => '禁售',
            self::DELETED->value     => '删除',
            self::DRAFT->value       => '未发布',

        ];
    }
}
