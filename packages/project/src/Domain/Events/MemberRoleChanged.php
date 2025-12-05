<?php

namespace RedJasmine\Project\Domain\Events;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Project\Domain\Models\ProjectRole;
use RedJasmine\Support\Domain\Contracts\UserInterface;

class MemberRoleChanged
{
    public function __construct(
        public Project $project,
        public ProjectMember $member,
        public ?ProjectRole $oldRole,
        public ProjectRole $newRole,
        public UserInterface $operator
    ) {
    }
}
