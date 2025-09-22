<?php

namespace RedJasmine\Organization\Application\Services\Member\Queries;

class PaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{
    public ?int $orgId;
    public ?int $departmentId;
    public ?string $mobile;
}


