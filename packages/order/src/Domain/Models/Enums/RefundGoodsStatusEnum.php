<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RefundGoodsStatusEnum: string
{
    use EnumsHelper;

    case  BUYER_NOT_RECEIVED = 'buyer_not_received';
    case  BUYER_RECEIVED = 'buyer_received';
    case  BUYER_RETURNED_GOODS = 'buyer_returned_goods ';


    public static function labels() : array
    {
        return [

            self::BUYER_NOT_RECEIVED->value   => '买家未收到货',
            self::BUYER_RECEIVED->value       => '买家已收到货',
            self::BUYER_RETURNED_GOODS->value => '买家已退货',
        ];
    }


}
