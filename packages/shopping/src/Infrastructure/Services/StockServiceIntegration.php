<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Ecommerce\Domain\Data\StockInfo;
use RedJasmine\Product\Application\Stock\Services\Commands\StockCommand;
use RedJasmine\Product\Application\Stock\Services\Queries\FindSkuStockQuery;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;

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


    public function getStockInfo(ProductIdentity $product, int $quantity) : StockInfo
    {
        $sku                        = $this->getSku($product);
        $cartStockInfo              = new StockInfo();
        $cartStockInfo->stock       = $sku->getSaleStock();
        $cartStockInfo->isAvailable = $cartStockInfo->stock > $quantity;

        return $cartStockInfo;
    }

    public function subStock(ProductIdentity $product, int $quantity, string $orderProductNo) : bool
    {
        $command               = new  StockCommand;
        $command->productId    = $product->id;
        $command->skuId        = $product->skuId;
        $command->actionStock  = $quantity;
        $command->changeType   = ProductStockChangeTypeEnum::SALE;
        $command->changeDetail = $orderProductNo;
        return $this->stockApplicationService->sub($command);
    }

    public function lockStock(ProductIdentity $product, int $quantity, string $orderProductNo) : bool
    {
        $command               = new  StockCommand;
        $command->productId    = $product->id;
        $command->skuId        = $product->skuId;
        $command->actionStock  = $quantity;
        $command->changeType   = ProductStockChangeTypeEnum::SALE;
        $command->changeDetail = $orderProductNo;
        return $this->stockApplicationService->lock($command);
    }


}