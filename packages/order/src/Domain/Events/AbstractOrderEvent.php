<?php

namespace RedJasmine\Order\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Order\Domain\Models\Order;

/**
 * 领域事件
 */
abstract class AbstractOrderEvent
{
    use Dispatchable;

    public function __construct(
        public readonly Order $order
    )
    {
    }
}
