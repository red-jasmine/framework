<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RefundGoodsStatusEnum: string
{
    use EnumsHelper;

    case  NOT_RECEIVED = 'not_received';
    case  RECEIVED = 'received';
    case  RETURNED = 'returned ';


    public static function labels() : array
    {
        return [

            self::NOT_RECEIVED->value => '未收到货',
            self::RECEIVED->value     => '已收到货',
            self::RETURNED->value     => '已退货',
        ];
    }


}
