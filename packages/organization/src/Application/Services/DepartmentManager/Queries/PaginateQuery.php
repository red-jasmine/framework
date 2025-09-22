<?php

namespace RedJasmine\Organization\Application\Services\DepartmentManager\Queries;

class PaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{
    public ?int $departmentId;
    public ?int $memberId;
}


