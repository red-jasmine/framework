<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderPaymentData;
use RedJasmine\Ecommerce\Domain\Data\Payment\PaymentTradeData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

/**
 * 积分商城订单服务接口
 * - 创建积分兑换订单
 * - 更新订单状态
 * - 获取订单信息
 */
interface OrderServiceInterface
{

    public const  BIZ = 'points-mall';
    /**
     * 获取订单商品拆分键
     *
     * @param  ProductPurchaseFactor  $orderProductData
     *
     * @return string
     */
    public function getOrderProductSplitKey(ProductPurchaseFactor $orderProductData) : string;


    public function create(PointsExchangeOrder $exchangeOrder, ProductInfo $productInfo);

    /**
     * 创建订单支付单
     *
     * @param  PointsExchangeOrder  $exchangeOrder
     *
     * @return PaymentTradeData
     */
    public function createPayment(PointsExchangeOrder $exchangeOrder) : PaymentTradeData;

    /**
     * 支付完成支付单
     *
     * @param  PointsExchangeOrder  $exchangeOrder
     * @param  OrderPaymentData  $orderPaymentData
     *
     * @return bool
     */
    public function paidOrderPayment(PointsExchangeOrder $exchangeOrder, OrderPaymentData $orderPaymentData) : bool;


    /**
     * 更新订单状态
     *
     * @param  string  $orderNo  订单号
     * @param  string  $status  订单状态
     * @param  array  $metadata  元数据
     *
     * @return bool
     */
    public function updateOrderStatus(string $orderNo, string $status, array $metadata = []) : bool;

    /**
     * 获取订单信息
     *
     * @param  string  $orderNo  订单号
     *
     * @return array|null
     */
    public function getOrderInfo(string $orderNo) : ?array;

    /**
     * 验证订单状态
     *
     * @param  string  $orderNo  订单号
     * @param  string  $expectedStatus  期望的订单状态
     *
     * @return bool
     */
    public function validateOrderStatus(string $orderNo, string $expectedStatus) : bool;

    /**
     * 创建积分兑换订单数据
     *
     * @param  array  $exchangeData  兑换数据
     *
     * @return array
     */
    public function createPointsExchangeOrderData(array $exchangeData) : array;

    /**
     * 取消订单
     *
     * @param  string  $orderNo  订单号
     * @param  string  $reason  取消原因
     *
     * @return bool
     */
    public function cancelOrder(string $orderNo, string $reason = '') : bool;

    /**
     * 获取用户订单列表
     *
     * @param  string  $ownerType  所属者类型
     * @param  string  $ownerId  所属者ID
     * @param  int  $limit  限制数量
     *
     * @return array
     */
    public function getUserOrders(string $ownerType, string $ownerId, int $limit = 20) : array;
} 