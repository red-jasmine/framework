<?php

namespace RedJasmine\Project\Domain\Data;

use RedJasmine\Project\Domain\Models\Enums\ProjectRoleStatus;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ProjectRoleData extends Data
{
    public ?string $id = null;
    public ?string $projectId = null;
    public string $name;
    public string $code;
    public string $description;
    public bool $isSystem = false;
    public ?array $permissions = null;
    public int $sort = 0;

    #[WithCast(EnumCast::class, ProjectRoleStatus::class)]
    public ProjectRoleStatus $status = ProjectRoleStatus::ACTIVE;
}
