<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TradeStatusEnum: string
{

    use EnumsHelper;

    case  PRE = 'pre'; // 等待支付
    case  PAYING = 'paying'; // 支付中
    case  PENDING = 'pending'; //
    case  SUCCESS = 'success'; // 成功有效订单
    case  REFUND = 'refund'; // 全款退
    case  CLOSED = 'closed'; // 操时关闭
    case  CANCEL = 'cancel'; // 取消
    case  FAIL = 'fail'; // 支付失败
    case  FINISH = 'finish'; // 支付结束


    public static function labels() : array
    {
        return [

            self::PRE->value     => '等待',
            self::SUCCESS->value => '成功',
            self::CLOSED->value  => '已关闭',
            self::CANCEL->value  => '已取消',
            self::REFUND->value  => '已退款',
            self::PAYING->value  => '支付中',
            self::FAIL->value    => '失败',
        ];
    }


}
