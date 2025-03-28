<?php

namespace RedJasmine\Product\Application\Property\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PropertyPaginateQuery extends PaginateQuery
{
    public ?string $name;
    public ?int    $groupId;
    public ?string $status;
    public ?string $type;

}
