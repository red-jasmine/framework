<?php

namespace RedJasmine\User\Domain\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use RedJasmine\User\Domain\Models\User;

abstract class AbstractUserEvent implements ShouldDispatchAfterCommit
{

    use Dispatchable;

    public function __construct(public readonly User $user)
    {
    }
}
