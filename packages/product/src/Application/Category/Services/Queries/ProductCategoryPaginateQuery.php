<?php

namespace RedJasmine\Product\Application\Category\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductCategoryPaginateQuery extends PaginateQuery
{

    public ?int $parentId = null;

}
