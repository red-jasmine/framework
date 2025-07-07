<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Order\Application\Services\Orders\OrderApplicationService;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderData;
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

    public function getOrderProductSplitKey(ProductPurchaseFactor $orderProductData) : string
    {
        if (!isset($orderProductData->product->seller)) {
            return '-';
        }
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
     * @return OrderData
     */
    public function create(OrderData $orderData) : OrderData
    {
        // 转换 DTO
        $command = new OrderCreateCommandTransformer()->transform($orderData);
        // 创建订单
        // TODO 如何关联 传入的订单项目
        $order = $this->orderApplicationService->create($command);


        $orderData->setOrderNo($order->order_no);
        foreach ($order->products as $product) {
            $product->getSerialNumber();
            foreach ($orderData->products as $productData) {
                if ($productData->getSerialNumber() == $product->getSerialNumber()) {
                    $productData->setOrderProductNo($product->order_product_no);
                }
            }
        }

        return $orderData;
    }


}