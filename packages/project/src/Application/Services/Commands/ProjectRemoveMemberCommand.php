<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class ProjectRemoveMemberCommand extends Data
{
    public ?string $reason = null;
}
