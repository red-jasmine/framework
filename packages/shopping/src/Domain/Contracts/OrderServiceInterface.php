<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;

/**
 * 订单服务接口
 * - 创建订单
 * - 发起支付
 */
interface OrderServiceInterface
{

    public function getOrderProductSplitKey(ProductPurchaseFactor $orderProductData) : string;

    /**
     * 创建订单
     *
     * @param  OrderData  $orderData
     *
     * @return OrderData
     *
     */
    public function create(OrderData $orderData) : OrderData;


    public function find(string $orderNo);
}