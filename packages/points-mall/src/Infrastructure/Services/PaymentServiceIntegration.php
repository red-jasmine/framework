<?php

namespace RedJasmine\PointsMall\Infrastructure\Services;

use RedJasmine\Payment\Application\Services\PaymentApplicationService;
use RedJasmine\PointsMall\Domain\Contracts\PaymentServiceInterface;

/**
 * 积分商城支付服务集成
 * 对接支付领域的应用服务
 */
class PaymentServiceIntegration implements PaymentServiceInterface
{
    public function __construct(
        protected PaymentApplicationService $paymentApplicationService,
    ) {
    }

    /**
     * 创建支付交易
     *
     * @param array $paymentData
     * @return string|null
     */
    public function createPayment(array $paymentData): ?string
    {
        try {
            $trade = $this->paymentApplicationService->create($paymentData);
            return $trade->trade_no ?? null;
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * 获取支付状态
     *
     * @param string $tradeNo
     * @return string|null
     */
    public function getPaymentStatus(string $tradeNo): ?string
    {
        try {
            $trade = $this->paymentApplicationService->find($tradeNo);
            return $trade->status ?? null;
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * 验证支付状态
     *
     * @param string $tradeNo
     * @param string $expectedStatus
     * @return bool
     */
    public function validatePaymentStatus(string $tradeNo, string $expectedStatus): bool
    {
        try {
            $trade = $this->paymentApplicationService->find($tradeNo);
            return $trade && $trade->status === $expectedStatus;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * 创建积分兑换支付数据
     *
     * @param array $exchangeData
     * @return array
     */
    public function createPointsExchangePaymentData(array $exchangeData): array
    {
        return [
            'order_no' => $exchangeData['outer_order_no'],
            'amount' => $exchangeData['price_amount'] * $exchangeData['quantity'],
            'currency' => $exchangeData['price_currency'],
            'payment_method' => $exchangeData['payment_method'] ?? 'wallet',
            'payment_type' => 'points_exchange',
            'metadata' => [
                'points_exchange_order_id' => $exchangeData['id'],
                'point_product_id' => $exchangeData['point_product_id'],
                'points' => $exchangeData['point'],
                'payment_mode' => $exchangeData['payment_mode'],
            ]
        ];
    }

    /**
     * 处理支付回调
     *
     * @param string $tradeNo
     * @param string $status
     * @param array $callbackData
     * @return bool
     */
    public function handlePaymentCallback(string $tradeNo, string $status, array $callbackData): bool
    {
        try {
            return $this->paymentApplicationService->handleCallback($tradeNo, $status, $callbackData);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * 退款
     *
     * @param string $tradeNo
     * @param float $amount
     * @param string $reason
     * @return bool
     */
    public function refund(string $tradeNo, float $amount, string $reason = ''): bool
    {
        try {
            return $this->paymentApplicationService->refund($tradeNo, $amount, $reason);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * 获取支付方式列表
     *
     * @return array
     */
    public function getPaymentMethods(): array
    {
        try {
            return $this->paymentApplicationService->getMethods();
        } catch (\Throwable $throwable) {
            return [];
        }
    }

    /**
     * 验证支付方式
     *
     * @param string $paymentMethod
     * @return bool
     */
    public function validatePaymentMethod(string $paymentMethod): bool
    {
        try {
            return $this->paymentApplicationService->validateMethod($paymentMethod);
        } catch (\Throwable $throwable) {
            return false;
        }
    }
} 