<?php

namespace RedJasmine\Payment\Domain\Events\Notifies;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Payment\Domain\Models\Notify;

class NotifyCreateEvent implements ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(public readonly Notify $notify)
    {
    }
}
