<?php

namespace RedJasmine\Ecommerce\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 商品 类型
 */
enum ProductTypeEnum: string
{
    use EnumsHelper;

    case GOODS = 'goods'; // 实物

    case VIRTUAL = 'virtual'; // 虚拟

    case TICKET = 'ticket'; // 票据

    case SERVICE = 'service'; // 服务

    // 服务

    public static function labels() : array
    {
        return [
            self::GOODS->value   => '普通',
            self::VIRTUAL->value => '虚拟',
            self::TICKET->value  => '票据',
            self::SERVICE->value => '服务',
        ];
    }

    public static function icons() : array
    {
        return [
            self::GOODS->value  => 'heroicon-o-briefcase',
            self::VIRTUAL->value    => 'heroicon-o-chart-bar-square',
            self::TICKET->value      => 'heroicon-o-ticket',
            self::SERVICE->value => 'heroicon-o-shield-check',

        ];
    }
}
