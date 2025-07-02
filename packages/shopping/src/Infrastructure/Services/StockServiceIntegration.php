<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Product\Application\Stock\Services\Queries\FindSkuStockQuery;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Data\CartStockInfo;

class StockServiceIntegration implements StockServiceInterface
{
    public function __construct(

        protected StockApplicationService $stockApplicationService,
    ) {
    }

    protected function getSku(ProductIdentity $product) : ProductSku
    {
        $query = FindSkuStockQuery::from([]);
        $query->setKey($product->skuId);

        return $this->stockApplicationService->find($query);
    }


    public function getAvailableStock(ProductIdentity $product, int $quantity) : CartStockInfo
    {
        $sku                        = $this->getSku($product);
        $cartStockInfo              = new CartStockInfo();
        $cartStockInfo->stock       = $sku->getSaleStock();
        $cartStockInfo->isAvailable = $cartStockInfo->stock > $quantity;

        return $cartStockInfo;
    }

    public function reserveStock(ProductIdentity $product, int $quantity, string $orderId) : bool
    {
        // TODO: Implement reserveStock() method.
    }

    public function releaseStock(ProductIdentity $product, int $quantity, string $orderId) : bool
    {
        // TODO: Implement releaseStock() method.
    }


}