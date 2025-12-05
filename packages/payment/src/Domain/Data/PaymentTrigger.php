<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Payment\Domain\Models\Enums\PaymentTriggerTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;

class PaymentTrigger extends Data
{
    // 支付展示方式

    public PaymentTriggerTypeEnum $type;

    public mixed $content = null;


}
