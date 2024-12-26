<?php

namespace RedJasmine\Payment\Domain\Events\Trades;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\Trade;

abstract class AbstractTradeEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;


    public function __construct(public Trade $trade)
    {
    }
}
