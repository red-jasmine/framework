<?php

namespace RedJasmine\Project\Application\Services\Queries;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProjectMemberListQuery extends PaginateQuery
{
    public string $projectId;
    public ?string $status = null;
    public ?string $memberType = null;
    public ?UserInterface $member = null;
}
