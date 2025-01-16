<?php

namespace RedJasmine\Payment\Domain\Events\Settles;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\Settle;

abstract class AbstractSettleEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;


    public function __construct(public Settle $settle)
    {
    }
}
