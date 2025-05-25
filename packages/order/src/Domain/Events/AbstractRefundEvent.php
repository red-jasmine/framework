<?php

namespace RedJasmine\Order\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Order\Domain\Models\Refund;

class AbstractRefundEvent
{

    use Dispatchable;

    public function __construct(public readonly Refund $orderRefund)
    {
    }


}
