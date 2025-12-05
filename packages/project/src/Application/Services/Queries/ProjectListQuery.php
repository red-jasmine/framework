<?php

namespace RedJasmine\Project\Application\Services\Queries;

use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProjectListQuery extends PaginateQuery
{
    public ?UserInterface $owner = null;
    public ?string $name = null;
    public ?string $code = null;
    public ?string $status = null;
    public ?string $projectType = null;
    public ?string $parentId = null;
    public ?bool $isShow = null;
}
