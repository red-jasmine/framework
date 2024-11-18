<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum MerchantStatusEnum: string
{
    use EnumsHelper;


    case  ENABLE = 'enable';// 启用

    case  DISABLED = 'disabled';// 禁用


    public static function labels() : array
    {
        return [
            self::ENABLE->value   => '启用',
            self::DISABLED->value => '禁用',
        ];

    }

}
