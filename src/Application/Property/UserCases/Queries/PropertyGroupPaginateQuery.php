<?php

namespace RedJasmine\Product\Application\Property\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PropertyGroupPaginateQuery extends PaginateQuery
{
    public ?string $name;

    public ?string $status;


}
