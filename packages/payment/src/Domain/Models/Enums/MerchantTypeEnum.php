<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 商户类型
 */
enum MerchantTypeEnum: string
{
    use EnumsHelper;

    // 普通商户
    case GENERAL = 'general';

    // 二级商户
    case SUB = 'sub';


    public static function labels() : array
    {
        return [
            self::GENERAL->value => '普通商户',
            self::SUB->value     => '二级商户',
        ];
    }


}
