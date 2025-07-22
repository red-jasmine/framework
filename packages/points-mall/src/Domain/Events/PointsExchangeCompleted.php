<?php

namespace RedJasmine\PointsMall\Domain\Events;

use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

class PointsExchangeCompleted
{
    public function __construct(
        public readonly PointsExchangeOrder $order
    ) {
    }
} 