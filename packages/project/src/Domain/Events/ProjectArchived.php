<?php

namespace RedJasmine\Project\Domain\Events;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Domain\Contracts\UserInterface;

class ProjectArchived
{
    public function __construct(
        public Project $project,
        public UserInterface $operator
    ) {
    }
}
