<?php

namespace RedJasmine\PointsMall\Domain\Contracts;

interface StatisticsServiceInterface
{
    /**
     * 记录积分兑换事件
     * 
     * @param array $exchangeData 兑换数据
     * @return bool 是否记录成功
     */
    public function recordExchangeEvent(array $exchangeData): bool;

    /**
     * 记录积分使用统计
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param int $points 使用的积分
     * @param string $productId 商品ID
     * @return bool 是否记录成功
     */
    public function recordPointsUsage(string $ownerType, string $ownerId, int $points, string $productId): bool;

    /**
     * 记录商品兑换统计
     * 
     * @param string $productId 商品ID
     * @param int $quantity 兑换数量
     * @param array $exchangeData 兑换数据
     * @return bool 是否记录成功
     */
    public function recordProductExchange(string $productId, int $quantity, array $exchangeData): bool;

    /**
     * 记录支付模式统计
     * 
     * @param string $paymentMode 支付模式
     * @param array $exchangeData 兑换数据
     * @return bool 是否记录成功
     */
    public function recordPaymentModeUsage(string $paymentMode, array $exchangeData): bool;

    /**
     * 获取积分兑换统计
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param string $period 统计周期 (daily, weekly, monthly)
     * @return array 统计数据
     */
    public function getExchangeStatistics(string $ownerType, string $ownerId, string $period = 'daily'): array;

    /**
     * 获取商品兑换统计
     * 
     * @param string $productId 商品ID
     * @param string $period 统计周期
     * @return array 统计数据
     */
    public function getProductExchangeStatistics(string $productId, string $period = 'daily'): array;

    /**
     * 获取积分使用统计
     * 
     * @param string $ownerType 所属者类型
     * @param string $ownerId 所属者ID
     * @param string $period 统计周期
     * @return array 统计数据
     */
    public function getPointsUsageStatistics(string $ownerType, string $ownerId, string $period = 'daily'): array;

    /**
     * 获取支付模式统计
     * 
     * @param string $period 统计周期
     * @return array 统计数据
     */
    public function getPaymentModeStatistics(string $period = 'daily'): array;
} 