<?php

namespace RedJasmine\Payment\Domain\Events\Trades;

use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\Trade;

abstract class AbstractTradeEvent
{

    use Dispatchable;


    public function __construct(protected Trade $trade)
    {
    }
}
