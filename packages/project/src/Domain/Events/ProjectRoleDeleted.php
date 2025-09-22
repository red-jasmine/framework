<?php

namespace RedJasmine\Project\Domain\Events;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Contracts\UserInterface;

class ProjectRoleDeleted
{
    public function __construct(
        public Project $project,
        public ProjectRole $role,
        public UserInterface $operator
    ) {
    }
}
