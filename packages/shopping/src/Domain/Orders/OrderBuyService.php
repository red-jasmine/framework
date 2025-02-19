<?php

namespace RedJasmine\Shopping\Domain\Orders;

use Illuminate\Support\Collection;
use RedJasmine\Order\Application\Services\Orders\OrderCommandService;
use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Product\Application\Stock\Services\StockCommandService;
use RedJasmine\Shopping\Domain\Orders\Data\OrderData;
use RedJasmine\Shopping\Domain\Orders\Data\OrdersData;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderCreateHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderToOrderDomainCreateHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderTransformHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderTranslateHook;
use RedJasmine\Shopping\Domain\Orders\Hooks\ShoppingOrderTranslateOrderDomainCommandHook;
use RedJasmine\Shopping\Domain\Orders\Pipelines\OrderCreateProductStockPipeline;
use RedJasmine\Shopping\Domain\Orders\Transformers\OrderCreateCommandTransformer;
use RedJasmine\Support\Foundation\Service\Service;
use Throwable;

class OrderBuyService extends Service
{

    public function __construct(
        protected StockCommandService $stockCommandService,
        protected OrderCommandService $orderCommandService,

    ) {

        // 创建流程中 扣减库存
        ShoppingOrderCreateHook::register(OrderCreateProductStockPipeline::class);
    }

    /**
     * @param  OrdersData  $ordersData
     *
     * @return Collection<Order>
     * @throws Throwable
     */
    public function buy(OrdersData $ordersData) : \Illuminate\Support\Collection
    {

        $orders = collect();
        foreach ($ordersData->orders as $orderData) {
            $orders[] = ShoppingOrderCreateHook::hook($orderData, fn() => $this->createOrderCore($orderData));
        }
        return $orders;


    }

    /**
     * 订单创建核心流程
     *
     * @param  OrderData  $orderData
     *
     * @return Order
     * @throws Throwable
     */
    protected function createOrderCore(OrderData $orderData) : Order
    {
        // 1、下订单
        //  转换 订单领域 OrderCommand
        $orderCommand = ShoppingOrderTransformHook::hook($orderData,
            fn($orderData) => app(OrderCreateCommandTransformer::class)->transform($orderData)
        );

        // 调用 订单领域 创建
        return ShoppingOrderToOrderDomainCreateHook::hook(
            $orderCommand,
            fn($orderCommand) => $this->orderCommandService->create($orderCommand)
        );
    }


}
