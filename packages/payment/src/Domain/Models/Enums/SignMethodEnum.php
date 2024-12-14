<?php

namespace RedJasmine\Payment\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum SignMethodEnum: string
{

    use EnumsHelper;

    case Secret = 'secret';

    case  Cert = 'cert';

}
