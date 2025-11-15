<?php

namespace RedJasmine\Product\Application\Price\Services\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class ProductPriceListQuery extends PaginateQuery
{
    public ?int $productId = null;
    public ?int $variantId = null;
    public ?string $market = null;
    public ?string $store = null;
    public ?string $userLevel = null;
}

