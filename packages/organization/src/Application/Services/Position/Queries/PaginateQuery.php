<?php

namespace RedJasmine\Organization\Application\Services\Position\Queries;

class PaginateQuery extends \RedJasmine\Support\Domain\Data\Queries\PaginateQuery
{
    public ?int $parentId;
    public ?string $sequence;
}


