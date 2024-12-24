<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RefundStatusEnum: string
{

    use EnumsHelper;

    case  PRE = 'pre'; // 预创建
    case  PROCESSING = 'processing'; // // 处理中
    case  REFUNDED = 'refunded'; // 成功
    case  CLOSED = 'closed'; // 关闭
    case  CANCEL = 'cancel'; // 取消
    case  ABNORMAL = 'abnormal'; // 异常


    public static function labels() : array
    {
        return [
            self::PRE->value        => '等待中',
            self::PROCESSING->value => '处理中',
            self::REFUNDED->value   => '已退款',
            self::CLOSED->value     => '已关闭',
            self::CANCEL->value     => '已取消',
            self::ABNORMAL->value   => '异常',

        ];
    }


}
