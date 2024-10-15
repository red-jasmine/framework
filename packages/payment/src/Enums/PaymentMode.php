<?php

namespace RedJasmine\Payment\Enums;

enum PaymentMode: string
{
    case  WEB = 'WEB';

    case  WAP = 'WAP';

    case APP = 'APP';

    case MINI_APP = 'MINI_APP';

    case QRCODE = 'QRCODE';

    case FACE = 'FACE';


    public static function options() : array
    {
        return [
            self::WEB->value      => '网页支付',
            self::WAP->value      => '手机网页',
            self::APP->value      => '应用程序',
            self::MINI_APP->value => '小程序',
            self::QRCODE->value   => '扫码',
            self::FACE->value     => '扫脸',
        ];
    }

}
