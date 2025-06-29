<?php

namespace RedJasmine\Admin\Domain\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\Admin\Domain\Models\Admin;

abstract class AbstractUserEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;

    public function __construct(public readonly Admin $user)
    {
    }
}
