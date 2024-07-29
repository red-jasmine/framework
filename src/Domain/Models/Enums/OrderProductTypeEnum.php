<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum OrderProductTypeEnum: string
{
    use EnumsHelper;

    case GOODS = 'goods'; // 实物

    case VIRTUAL = 'virtual'; // 虚拟

    case TICKET = 'ticket'; // 票据

    case SERVICE = 'SERVICE'; // 服务

    // 服务

    public static function labels() : array
    {
        return [
            self::GOODS->value   => '普通',
            self::VIRTUAL->value => '虚拟',
            self::TICKET->value  => '票据',
            self::SERVICE->value => '票据',
        ];
    }
}
