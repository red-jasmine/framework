<?php

namespace RedJasmine\Organization\Application\Services\MemberDepartment\Queries;

class PaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{
    public ?int $memberId;
    public ?int $departmentId;
}


