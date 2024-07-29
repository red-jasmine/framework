<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PromiseServiceTypeEnum: string
{

    use EnumsHelper;

    case REFUND = 'refund';

    case EXCHANGE = 'exchange';

    case SERVICE = 'service';



    public static function labels() : array
    {

        return [
            self::REFUND->value    => '退款',
            self::EXCHANGE->value  => '换货',
            self::SERVICE->value   => '保修',
        ];
    }

}
