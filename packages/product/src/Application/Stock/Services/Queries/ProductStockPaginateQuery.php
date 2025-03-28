<?php

namespace RedJasmine\Product\Application\Stock\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductStockPaginateQuery extends PaginateQuery
{

    public ?string $ownerType;
    public ?string $ownerId;
    public ?int    $productId;
    public ?int    $skuId;


}
