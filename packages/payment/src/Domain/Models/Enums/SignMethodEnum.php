<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 签名模式
 */
enum SignMethodEnum: string
{

    use EnumsHelper;

    case Secret = 'secret';

    case  Cert = 'cert';


    public static function labels() : array
    {
        return [
            self::Secret->value => '密钥',
            self::Cert->value   => '证书',
        ];
    }

}
