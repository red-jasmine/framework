<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TransferStatusEnum: string
{

    use EnumsHelper;


    case  PRE = 'pre'; // 预创建

    // 中间状态
    case  PROCESSING = 'processing'; // // 处理中
    case  FAIL = 'fail'; // 支付失败
    // 最终态
    case  REFUND = 'refund'; // 操时
    case  CLOSED = 'closed'; // 操时
    case  CANCEL = 'cancel'; // 取消
    case  SUCCESS = 'success'; // 支持成功


    public static function labels() : array
    {
        return [
            self::PRE->value        => '预创建',
            self::PROCESSING->value => '处理中',
            self::FAIL->value       => '支付失败',
            self::CLOSED->value     => '操作关闭',
            self::CANCEL->value     => '取消',
            self::SUCCESS->value    => '支付成功',
            self::REFUND->value     => '已退回',
        ];
    }


}
