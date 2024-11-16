<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum MerchantStatusEnum: string
{
    use EnumsHelper;


    case  Enable = 'Enable';// 启用
    case  Disabled = 'disabled';// 禁用


    public static function labels() : array
    {
        return [
            self::Enable->value   => '启用',
            self::Disabled->value => '禁用',
        ];

    }

}
