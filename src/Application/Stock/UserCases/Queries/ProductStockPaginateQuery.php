<?php

namespace RedJasmine\Product\Application\Stock\UserCases\Queries;

use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class ProductStockPaginateQuery extends PaginateQuery
{

    public ?string $ownerType;
    public ?string $ownerId;
    public ?int    $productId;
    public ?int    $skuId;


}
