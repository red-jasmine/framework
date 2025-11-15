<?php

namespace RedJasmine\Product\Application\Price\Services\Queries;

use RedJasmine\Product\Application\Price\Services\PriceApplicationService;
use RedJasmine\Product\Domain\Price\Models\ProductVariantPrice;
use RedJasmine\Support\Application\Queries\QueryHandler;

class GetProductPriceQueryHandler extends QueryHandler
{
    public function __construct(
        protected PriceApplicationService $service
    ) {
    }

    /**
     * 处理变体价格查询
     *
     * @param GetProductPriceQuery $query
     * @return ProductVariantPrice|null
     */
    public function handle(GetProductPriceQuery $query): ?ProductVariantPrice
    {
        return $this->service->getPrice($query);
    }
}

