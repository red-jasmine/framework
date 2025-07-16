<?php

namespace RedJasmine\Coupon\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum RuleCheckTypeEnum: string
{
    use EnumsHelper;

    case  USAGE = 'usage';
    case RECEIVE = 'receive';
}
