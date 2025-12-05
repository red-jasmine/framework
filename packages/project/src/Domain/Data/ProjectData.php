<?php

namespace RedJasmine\Project\Domain\Data;

use RedJasmine\Project\Domain\Models\Enums\ProjectStatus;
use RedJasmine\Project\Domain\Models\Enums\ProjectType;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class ProjectData extends Data
{
    public ?string $id = null;
    public UserInterface $owner;
    public ?string $parentId = null;
    public string $name;
    public ?string $shortName = null;
    public ?string $description = null;
    public string $code;

    #[WithCast(EnumCast::class, ProjectType::class)]
    public ProjectType $projectType = ProjectType::STANDARD;

    #[WithCast(EnumCast::class, ProjectStatus::class)]
    public ProjectStatus $status = ProjectStatus::DRAFT;

    public int $sort = 0;
    public ?array $config = null;
}
