<?php

namespace RedJasmine\Order\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Order\Domain\Models\OrderLogistics;

class OrderLogisticsChangedStatusEvent
{
    use Dispatchable;

    public function __construct(
        public readonly OrderLogistics $orderLogistics
    )
    {
    }
}
