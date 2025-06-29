<?php

namespace RedJasmine\Shop\Domain\Events;

use RedJasmine\Shop\Domain\Models\Shop;

class ShopRegisteredEvent
{
    public function __construct(
        public Shop $shop
    ) {
    }
} 