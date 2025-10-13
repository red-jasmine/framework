<?php

namespace RedJasmine\Organization\Application\Services\Member\Commands;

use RedJasmine\Support\Data\Data;

class MemberResignCommand extends Data
{
    public int $memberId;
    public ?string $reason = null;
    public ?string $notes = null;
}
