<?php

namespace RedJasmine\Product\Application\Attribute\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductAttributeGroupPaginateQuery extends PaginateQuery
{
    public ?string $name;

    public ?string $status;


}
