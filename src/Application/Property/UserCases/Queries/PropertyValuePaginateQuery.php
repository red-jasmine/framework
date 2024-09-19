<?php

namespace RedJasmine\Product\Application\Property\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class PropertyValuePaginateQuery extends PaginateQuery
{

    public ?int    $pid;
    public ?string $name;
    public ?int    $groupId;
    public ?string $status;


}
