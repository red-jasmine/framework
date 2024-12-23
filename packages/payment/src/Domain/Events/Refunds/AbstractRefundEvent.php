<?php

namespace RedJasmine\Payment\Domain\Events\Refunds;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\Refund;

abstract class AbstractRefundEvent
{

    use Dispatchable;


    public function __construct(public readonly Refund $refund)
    {
    }
}
