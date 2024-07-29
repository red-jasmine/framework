<?php

namespace RedJasmine\Order\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Order\Domain\Models\OrderRefund;

class AbstractRefundEvent
{

    use Dispatchable;

    public function __construct(public readonly OrderRefund $orderRefund)
    {
    }


}
