<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 渠道产品类型
 */
enum ChannelProductTypeEnum: string
{

    use EnumsHelper;

    case  PAYMENT = 'payment';// 支付
    case  TRANSFER = 'transfer'; // 转账


    public static function labels() : array
    {
        return [
            self::PAYMENT->value  => '支付',
            self::TRANSFER->value => '转账',
        ];

    }

}
