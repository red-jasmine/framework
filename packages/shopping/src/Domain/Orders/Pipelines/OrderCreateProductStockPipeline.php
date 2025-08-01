<?php

namespace RedJasmine\Shopping\Domain\Orders\Pipelines;

use Closure;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Product\Application\Stock\Services\Commands\StockCommand;
use RedJasmine\Product\Application\Stock\Services\StockApplicationService;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;

class OrderCreateProductStockPipeline
{

    public function __construct(
        protected StockApplicationService $stockCommandService,

    )
    {
    }

    public function handle(OrderData $orderData, Closure $next)
    {
        // 扣减库存
        $this->subStock($orderData);

        return $next($orderData);
    }


    protected function subStock(OrderData $orderData) : void
    {
        foreach ($orderData->products as $productData) {
            $stockCommand               = new StockCommand();
            $stockCommand->productId    = $productData->productId;
            $stockCommand->skuId        = $productData->skuId;
            $stockCommand->actionStock  = $productData->quantity;
            $stockCommand->changeType   = ProductStockChangeTypeEnum::SELLER;
            $stockCommand->changeDetail = '';
            // 锁定库存
            $this->stockCommandService->sub($stockCommand);
        }
    }

}
