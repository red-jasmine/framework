<?php

namespace RedJasmine\Payment\Domain\Events\SettleReceivers;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\SettleReceiver;

abstract class AbstractSettleReceiverEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;


    public function __construct(public SettleReceiver $settleReceiver)
    {
    }
}
