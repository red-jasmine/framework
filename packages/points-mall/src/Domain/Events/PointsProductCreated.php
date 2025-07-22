<?php

namespace RedJasmine\PointsMall\Domain\Events;

use RedJasmine\PointsMall\Domain\Models\PointsProduct;

class PointsProductCreated
{
    public function __construct(
        public readonly PointsProduct $product
    ) {
    }
} 