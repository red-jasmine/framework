<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TradeStatusEnum: string
{

    use EnumsHelper;

    case  PRE = 'pre'; // 预创建
    case  PAYING = 'paying'; // 支付中
    case  PENDING = 'pending'; // // 处理中
    case  SUCCESS = 'success'; // 支持成功
    case  REFUND = 'refund'; // 全款退
    case  CLOSED = 'closed'; // 操时关闭
    case  CANCEL = 'cancel'; // 取消
    case  FAIL = 'fail'; // 支付失败
    case  FINISH = 'finish'; // 完成


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
