<?php

namespace RedJasmine\Distribution\Domain\Events\Promoters;

use Illuminate\Foundation\Events\Dispatchable;

class PromoterDowngradeEvent
{
    use Dispatchable;

    public function __construct()
    {
    }
}
