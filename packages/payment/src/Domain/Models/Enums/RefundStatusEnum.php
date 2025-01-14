<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RefundStatusEnum: string
{

    use EnumsHelper;

    // 开始状态
    case  PRE = 'pre'; // 预创建
    case  PENDING = 'pending'; //  待处理
    // 中间态
    case  PROCESSING = 'processing'; // // 处理中
    case  ABNORMAL = 'abnormal'; // 异常
    case  FAIL = 'fail'; // 失败
    // 最终态
    case  SUCCESS = 'success'; // 成功
    case  CLOSED = 'closed'; // 关闭
    case  CANCEL = 'cancel'; // 取消


    public static function labels() : array
    {
        return [
            self::PRE->value        => '等待中',
            self::PENDING->value    => '待处理',
            self::PROCESSING->value => '处理中',
            self::ABNORMAL->value   => '异常',
            self::FAIL->value       => '失败',
            self::SUCCESS->value    => '已退款',
            self::CLOSED->value     => '已关闭',
            self::CANCEL->value     => '已取消',
        ];
    }


}
