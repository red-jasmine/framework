<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

/**
 * 支付场景
 */
enum SceneEnum: string
{

    use EnumsHelper;

    case  WEB = 'web';

    case WAP = 'wap';

    case APP = 'app';

    case JSAPI = 'jsapi';

    case QRCODE = 'qrcode';

    case FACE = 'face';

    case PROTOCOL = 'protocol';

    case API = 'api';


    public static function labels() : array
    {

        return [
            self::WEB->value      => __('red-jasmine-payment::common.enums.method.web'),
            self::WAP->value      => __('red-jasmine-payment::common.enums.method.wap'),
            self::APP->value      => __('red-jasmine-payment::common.enums.method.app'),
            self::JSAPI->value    => __('red-jasmine-payment::common.enums.method.jsapi'),
            self::QRCODE->value   => __('red-jasmine-payment::common.enums.method.qrcode'),
            self::FACE->value     => __('red-jasmine-payment::common.enums.method.face'),
            self::PROTOCOL->value => __('red-jasmine-payment::common.enums.method.protocol'),
            self::API->value      => __('red-jasmine-payment::common.enums.method.api'),

        ];
    }


}
