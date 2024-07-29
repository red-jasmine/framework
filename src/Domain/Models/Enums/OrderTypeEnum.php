<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 订单类型
 */
enum OrderTypeEnum: string
{
    use EnumsHelper;

    case  SOP = 'sop';
    case  PRESALE = 'presale';
    case  GROUP_PURCHASE = 'group_purchase';
    // 拍卖


    public static function labels() : array
    {
        return [
            self::SOP->value            => '标准',
            self::PRESALE->value        => '预售',
            self::GROUP_PURCHASE->value => '团购',
        ];
    }


}
