<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SettleStatusEnum: string
{
    use EnumsHelper;

    case  PRE = 'pre'; // 预创建
    case  PENDING = 'pending'; //  待处理
    // 中间状态
    case  PROCESSING = 'processing'; // 处理中
    case  ABNORMAL = 'abnormal'; // 异常经过确认
    case  FAIL = 'fail'; // 支付失败
    // 最终态
    case  SUCCESS = 'success'; // 成功
    case  CLOSED = 'closed'; // 关闭
    case  CANCEL = 'cancel'; // 取消


    public static function labels() : array
    {
        return [
            self::PRE->value        => '预创建',
            self::PENDING->value    => '待处理',
            self::PROCESSING->value => '处理中',
            self::FAIL->value       => '失败',
            self::CLOSED->value     => '关闭',
            self::CANCEL->value     => '取消',
            self::SUCCESS->value    => '成功',
        ];
    }


}
