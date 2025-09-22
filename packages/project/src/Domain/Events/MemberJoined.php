<?php

namespace RedJasmine\Project\Domain\Events;

use RedJasmine\Project\Domain\Models\Project;
use RedJasmine\Project\Domain\Models\ProjectMember;
use RedJasmine\Support\Contracts\UserInterface;

class MemberJoined
{
    public function __construct(
        public Project $project,
        public ProjectMember $member,
        public ?UserInterface $inviter = null
    ) {
    }
}
