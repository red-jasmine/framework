<?php

namespace RedJasmine\Order\Domain\Models\Enums\CardKeys;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;


enum OrderCardKeyContentTypeEnum: string
{
    use EnumsHelper;

    case   TEXT = 'text';
    case   QRCODE = 'qrcode';

    public static function labels() : array
    {
        return [
            self::TEXT->value   => __('red-jasmine-order::card-keys.enums.content_type.text'),
            self::QRCODE->value => __('red-jasmine-order::card-keys.enums.content_type.qrcode'),

        ];
    }
}
