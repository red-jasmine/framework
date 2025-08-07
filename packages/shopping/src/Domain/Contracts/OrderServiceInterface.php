<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderPaymentData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeData;
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


    /**
     * 创建支付订单
     *
     * @param  string  $orderNo
     *
     * @return PaymentTradeData
     */
    public function createOrderPayment(string $orderNo) : PaymentTradeData;


    /**
     * 支付完成支付单
     *
     * @param  string  $orderNo
     * @param  int  $orderPaymentId
     * @param  OrderPaymentData  $orderPaymentData
     *
     * @return bool
     */
    public function paidOrderPayment(string $orderNo, int $orderPaymentId, OrderPaymentData $orderPaymentData) : bool;


}