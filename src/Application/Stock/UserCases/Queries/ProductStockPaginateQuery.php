<?php

namespace RedJasmine\Product\Application\Stock\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class ProductStockPaginateQuery extends PaginateQuery
{

    public ?int $skuId;
    public ?int $productId;

}
