<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 转账人证件类型
 */
enum CertTypeEnum: string
{
    use EnumsHelper;

    // 身份证
    // 护照
    case ID_CARD = 'ID_CARD';
    case PASSPORT = 'PASSPORT';


    public static function labels() : array
    {
        return [
            self::ID_CARD->value  => '身份证',
            self::PASSPORT->value => '护照',
        ];
    }


}
