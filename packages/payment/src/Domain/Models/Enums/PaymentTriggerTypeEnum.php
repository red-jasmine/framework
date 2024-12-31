<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum PaymentTriggerTypeEnum: string
{
    use EnumsHelper;


    case QR_CODE = 'qr_code';
    case REDIRECT = 'redirect';
    case APP = 'app';
    case APPLET = 'applet';


    public static function labels() : array
    {
        return [
            self::QR_CODE->value  => '二维码',
            self::REDIRECT->value => '跳转',
            self::APP->value      => '应用',
            self::APPLET->value   => '小程序',
        ];

    }
}
