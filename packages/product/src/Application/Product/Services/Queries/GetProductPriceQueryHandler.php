<?php

namespace RedJasmine\Product\Application\Product\Services\Queries;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Product\Application\Product\Services\ProductApplicationService;
use RedJasmine\Product\Domain\Price\ProductPriceDomainService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class GetProductPriceQueryHandler extends QueryHandler
{

    protected ProductPriceDomainService $productPriceDomainService;

    public function __construct(
        protected ProductApplicationService $service,

    ) {
        $this->productPriceDomainService = app(ProductPriceDomainService::class, [
            'productRepository' => $this->service->repository
        ]);
    }

    public function handle(ProductPurchaseFactor $query) : ?Money
    {

        return $this->productPriceDomainService->getPrice($query);

    }


}