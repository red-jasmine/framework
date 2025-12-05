<?php

namespace RedJasmine\Project\Domain\Data;

use RedJasmine\Project\Domain\Models\Enums\ProjectMemberStatus;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ProjectMemberData extends Data
{
    public ?string $id = null;
    public string $projectId;
    public UserInterface $member;

    #[WithCast(EnumCast::class, ProjectMemberStatus::class)]
    public ProjectMemberStatus $status = ProjectMemberStatus::PENDING;

    public ?string $joinedAt = null;
    public ?string $leftAt = null;
    public ?UserInterface $invitedBy = null;
    public ?array $permissions = null;
}
