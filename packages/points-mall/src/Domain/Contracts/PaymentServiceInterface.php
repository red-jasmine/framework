<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

/**
 * 积分商城支付服务接口
 * - 创建支付交易
 * - 获取支付状态
 * - 处理支付回调
 * - 支付退款
 */
interface PaymentServiceInterface
{
    /**
     * 创建支付交易
     *
     * @param array $paymentData 支付数据
     * @return string|null 交易号
     */
    public function createPayment(array $paymentData): ?string;

    /**
     * 获取支付状态
     *
     * @param string $tradeNo 交易号
     * @return string|null 支付状态
     */
    public function getPaymentStatus(string $tradeNo): ?string;

    /**
     * 验证支付状态
     *
     * @param string $tradeNo 交易号
     * @param string $expectedStatus 期望的支付状态
     * @return bool
     */
    public function validatePaymentStatus(string $tradeNo, string $expectedStatus): bool;

    /**
     * 创建积分兑换支付数据
     *
     * @param array $exchangeData 兑换数据
     * @return array
     */
    public function createPointsExchangePaymentData(array $exchangeData): array;

    /**
     * 处理支付回调
     *
     * @param string $tradeNo 交易号
     * @param string $status 支付状态
     * @param array $callbackData 回调数据
     * @return bool
     */
    public function handlePaymentCallback(string $tradeNo, string $status, array $callbackData): bool;

    /**
     * 退款
     *
     * @param string $tradeNo 交易号
     * @param float $amount 退款金额
     * @param string $reason 退款原因
     * @return bool
     */
    public function refund(string $tradeNo, float $amount, string $reason = ''): bool;

    /**
     * 获取支付方式列表
     *
     * @return array
     */
    public function getPaymentMethods(): array;

    /**
     * 验证支付方式
     *
     * @param string $paymentMethod 支付方式
     * @return bool
     */
    public function validatePaymentMethod(string $paymentMethod): bool;
} 