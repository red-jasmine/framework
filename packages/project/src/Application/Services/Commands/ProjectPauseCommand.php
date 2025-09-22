<?php

namespace RedJasmine\Project\Application\Services\Commands;

use RedJasmine\Support\Data\Data;

class ProjectPauseCommand extends Data
{
    public ?string $reason = null;
}
