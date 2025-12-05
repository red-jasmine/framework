<?php

namespace RedJasmine\Project\Domain\Events;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Support\Domain\Contracts\UserInterface;

class MemberLeft
{
    public function __construct(
        public Project $project,
        public ProjectMember $member,
        public ?UserInterface $operator = null
    ) {
    }
}
