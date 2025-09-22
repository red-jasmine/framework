<?php

namespace RedJasmine\Project\Domain\Events;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Support\Contracts\UserInterface;

class ProjectCreated
{
    public function __construct(
        public Project $project,
        public UserInterface $creator
    ) {
    }
}
