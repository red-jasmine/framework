<?php

namespace RedJasmine\Product\Application\Category\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductSellerCategoryPaginateQuery extends PaginateQuery
{

    public ?int $parentId = null;

    public ?string $ownerType = null;

    public ?int $ownerId   = null;
}
