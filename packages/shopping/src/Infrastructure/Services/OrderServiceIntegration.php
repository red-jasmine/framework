<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderData;
use RedJasmine\Shopping\Domain\Data\OrderProductData;
use RedJasmine\Shopping\Domain\Data\OrdersData;
use RedJasmine\Shopping\Infrastructure\Services\Transformers\OrderCreateCommandTransformer;

/**
 * 订单服务集成
 *
 */
class OrderServiceIntegration implements OrderServiceInterface
{

    public function __construct(
        protected OrderApplicationService $orderApplicationService,
    ) {
    }

    public function getOrderProductSplitKey(OrderProductData $orderProductData) : string
    {
        return md5(implode('|',
            [
                $orderProductData->product->seller->getType(),
                $orderProductData->product->seller->getID(),
            ]
        ));

    }


    /**
     * 创建订单
     *
     * @param  OrderData  $orderData
     *
     * @return mixed
     */
    public function create(OrderData $orderData) : string
    {
        // 转换 DTO
        $command = app(OrderCreateCommandTransformer::class)->transform($orderData);

        // 创建订单
        $order = $this->orderApplicationService->create($command);
        return $order->order_no;
    }


}