<?php

namespace RedJasmine\Product\Application\Attribute\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductAttributePaginateQuery extends PaginateQuery
{
    public ?string $name;
    public ?int    $groupId;
    public ?string $status;
    public ?string $type;

}
