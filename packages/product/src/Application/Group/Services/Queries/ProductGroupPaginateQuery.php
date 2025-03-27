<?php

namespace RedJasmine\Product\Application\Group\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductGroupPaginateQuery extends PaginateQuery
{

    public ?int $parentId = null;

    public ?string $ownerType = null;

    public ?int $ownerId   = null;


    public ?string $name;
}
