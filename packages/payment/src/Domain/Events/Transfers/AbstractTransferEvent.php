<?php

namespace RedJasmine\Payment\Domain\Events\Transfers;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\Transfer;

abstract class AbstractTransferEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;


    public function __construct(public readonly Transfer $transfer)
    {
    }
}
