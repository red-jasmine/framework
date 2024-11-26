<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

enum PaymentMode: string
{
    case  WEB = 'web';

    case  WAP = 'wap';

    case APP = 'app';

    case MINI_APP = 'mini_app';

    case QRCODE = 'qrcode';

    case FACE = 'face';


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
