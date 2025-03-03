<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum ClientTypeEnum: string
{
    use EnumsHelper;

    case APP = 'app';

    case WEB = 'web';

    case  WAP = 'wap';

    case  APPLET = 'applet'; // 小程序

}
