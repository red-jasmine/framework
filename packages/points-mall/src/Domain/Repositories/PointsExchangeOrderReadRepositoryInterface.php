<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface PointsExchangeOrderReadRepositoryInterface extends ReadRepositoryInterface
{
    /**
     * 根据订单号查找订单
     */
    public function findByOrderNo(string $orderNo): ?\RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

    /**
     * 根据关联订单号查找订单
     */
    public function findByOuterOrderNo(string $outerOrderNo): ?\RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

    /**
     * 查找用户的订单
     */
    public function findByBuyer(string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection;

    /**
     * 统计用户兑换次数
     */
    public function countByBuyerAndProduct(string $ownerType, string $ownerId, string $productId): int;

    /**
     * 查找订单及其商品信息
     */
    public function findWithProduct(string $orderId): ?\RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;

    /**
     * 查找用户的订单列表
     */
    public function findUserOrders(string $ownerType, string $ownerId, int $limit = 20): \Illuminate\Database\Eloquent\Collection;
} 