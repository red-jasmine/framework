<?php

namespace RedJasmine\Product\Application\Property\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PropertyGroupPaginateQuery extends PaginateQuery
{
    public ?string $name;

    public ?string $status;


}
