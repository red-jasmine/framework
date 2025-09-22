<?php

namespace RedJasmine\Organization\Application\Services\Department\Queries;

class PaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{
    public ?int $orgId;
    public ?int $parentId;
}


