<?php

namespace RedJasmine\Product\Application\Stock\UserCases\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductStockLogPaginateQuery extends PaginateQuery
{

    public ?string $ownerType;
    public ?string $ownerId;
    public ?int    $productId;
    public ?int    $skuId;


}
