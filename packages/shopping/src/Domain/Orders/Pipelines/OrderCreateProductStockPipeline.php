<?php

namespace RedJasmine\Shopping\Domain\Orders\Pipelines;

use Closure;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Product\Application\Stock\UserCases\StockCommand;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Shopping\Domain\Orders\Data\OrderData;

class OrderCreateProductStockPipeline
{

    public function __construct(
        protected StockCommandService $stockCommandService,

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
            $stockCommand->actionStock  = $productData->num;
            $stockCommand->changeType   = ProductStockChangeTypeEnum::SELLER;
            $stockCommand->changeDetail = '';
            // 锁定库存
            $this->stockCommandService->sub($stockCommand);
        }
    }

}
