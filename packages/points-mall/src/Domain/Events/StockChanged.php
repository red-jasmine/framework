<?php

namespace RedJasmine\PointsMall\Domain\Events;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;

class StockChanged
{
    public function __construct(
        public readonly PointsProduct $product,
        public readonly int $oldStock,
        public readonly int $newStock,
        public readonly string $changeType
    ) {
    }
} 