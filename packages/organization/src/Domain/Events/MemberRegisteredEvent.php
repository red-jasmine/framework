<?php

namespace RedJasmine\Organization\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Organization\Domain\Models\Member;

class MemberRegisteredEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Member $member
    ) {
    }
}
