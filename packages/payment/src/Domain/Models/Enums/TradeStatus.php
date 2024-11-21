<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TradeStatus: string
{

    use EnumsHelper;

    case  WAIT = 'WAIT'; // 等待支付
    case  SUCCESS = 'SUCCESS'; // 成功有效订单
    case  REFUND = 'REFUND'; // 全款退
    case  CLOSED = 'CLOSED'; // 操时关闭
    case  PAYING = 'PAYING'; // 支付中
    case  FAIL = 'FAIL'; // 支付失败
    case  FINISH = 'FINISH'; // 支付结束

    public static function labels() : array
    {
        return [

            self::WAIT->value    => '等待',
            self::SUCCESS->value => '成功',
            self::CLOSED->value  => '已关闭',
            self::REFUND->value  => '已退款',
            self::PAYING->value  => '支付中',
            self::FAIL->value    => '失败',
        ];
    }


}
