<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Interaction\Application\Services\Queries\FindQuery;
use RedJasmine\Product\Application\Stock\Services\Queries\FindSkuStockQuery;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\ShoppingCart\Domain\Contracts\StockServiceInterface;
use RedJasmine\ShoppingCart\Domain\Data\CartStockInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;

class StockServiceIntegration implements StockServiceInterface
{
    public function __construct(

        protected StockApplicationService $stockApplicationService,
    ) {
    }

    protected function getSku(CartProduct $product) : ProductSku
    {
        $query = FindSkuStockQuery::from([]);
        $query->setKey($product->skuId);

        return $this->stockApplicationService->find($query);
    }

    public function checkStock(CartProduct $product, int $quantity) : bool
    {
        // 查询库存
        $query = FindSkuStockQuery::from([]);
        $query->setKey($product->skuId);

        $skuStock = $this->stockApplicationService->find($query);
        return $skuStock->getSaleStock() >= $quantity;
    }

    public function getAvailableStock(CartProduct $product, int $quantity) : CartStockInfo
    {
        $sku           = $this->getSku($product);
        $cartStockInfo = new CartStockInfo();

        $cartStockInfo->stock       = $sku->getSaleStock();
        $cartStockInfo->isAvailable = $cartStockInfo->stock > $quantity;

        return $cartStockInfo;
    }

    public function reserveStock(CartProduct $product, int $quantity, string $orderId) : bool
    {
        // TODO: Implement reserveStock() method.
    }

    public function releaseStock(CartProduct $product, int $quantity, string $orderId) : bool
    {
        // TODO: Implement releaseStock() method.
    }


}