<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

interface NotificationServiceInterface
{
    /**
     * 发送积分兑换成功通知
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param array $exchangeData 兑换数据
     * @return bool 是否发送成功
     */
    public function sendExchangeSuccessNotification(string $ownerType, string $ownerId, array $exchangeData): bool;

    /**
     * 发送积分兑换失败通知
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param array $exchangeData 兑换数据
     * @param string $reason 失败原因
     * @return bool 是否发送成功
     */
    public function sendExchangeFailureNotification(string $ownerType, string $ownerId, array $exchangeData, string $reason): bool;

    /**
     * 发送库存不足通知
     * 
     * @param string $productId 商品ID
     * @param array $productData 商品数据
     * @return bool 是否发送成功
     */
    public function sendLowStockNotification(string $productId, array $productData): bool;

    /**
     * 发送积分余额不足通知
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param int $requiredPoints 需要的积分
     * @param int $currentBalance 当前余额
     * @return bool 是否发送成功
     */
    public function sendInsufficientPointsNotification(string $ownerType, string $ownerId, int $requiredPoints, int $currentBalance): bool;

    /**
     * 发送订单状态变更通知
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param string $orderNo 订单号
     * @param string $status 订单状态
     * @return bool 是否发送成功
     */
    public function sendOrderStatusChangeNotification(string $ownerType, string $ownerId, string $orderNo, string $status): bool;

    /**
     * 发送支付成功通知
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param string $tradeNo 交易号
     * @param array $paymentData 支付数据
     * @return bool 是否发送成功
     */
    public function sendPaymentSuccessNotification(string $ownerType, string $ownerId, string $tradeNo, array $paymentData): bool;

    /**
     * 发送支付失败通知
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param string $tradeNo 交易号
     * @param string $reason 失败原因
     * @return bool 是否发送成功
     */
    public function sendPaymentFailureNotification(string $ownerType, string $ownerId, string $tradeNo, string $reason): bool;
} 