<?php

namespace RedJasmine\Project\Application\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProjectRoleListQuery extends PaginateQuery
{
    public string $projectId;
    public ?string $status = null;
    public ?bool $isSystem = null;
    public ?string $name = null;
    public ?string $code = null;
}
