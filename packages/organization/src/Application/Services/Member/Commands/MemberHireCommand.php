<?php

namespace RedJasmine\Organization\Application\Services\Member\Commands;

use RedJasmine\Support\Data\Data;

class MemberHireCommand extends Data
{
    public int $memberId;
    public ?string $notes = null;
}
