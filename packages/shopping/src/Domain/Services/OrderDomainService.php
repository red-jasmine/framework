<?php

namespace RedJasmine\Shopping\Domain\Services;

use RedJasmine\Shopping\Domain\Data\OrderAmountData;
use RedJasmine\Shopping\Domain\Data\OrderData;

/**
 * 订单结算服务
 */
class OrderDomainService extends AmountCalculationService
{


    /**
     * @param  OrderData  $orderData
     *
     * @return OrderAmountData
     */
    public function buy(OrderData $orderData) : OrderAmountData
    {
        // 进行订单拆分


        // 进行商品验证
        // 库存验证等
        // 优惠券验证

        // 下单时  进行扣减

        return $this->calculates($orderData);
    }

    public function calculates(OrderData $orderData) : OrderAmountData
    {
        foreach ($orderData->products as $index => $product) {
            $product->setKey($index);
            $product->buyer = $orderData->buyer;
        }
        return $this->getOrderAmount($orderData->products);

    }
}
