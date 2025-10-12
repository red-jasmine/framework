<?php

namespace RedJasmine\Product\Application\Attribute\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductAttributeValuePaginateQuery extends PaginateQuery
{

    public ?int    $pid;
    public ?string $name;
    public ?int    $groupId;
    public ?string $status;


}
