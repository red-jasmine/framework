<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

interface PointsExchangeOrderRepositoryInterface extends RepositoryInterface
{

    /**
     * 根据订单号查找订单
     */
    public function findByNo(string $no);

    /**
     * 根据关联订单号查找订单
     */
    public function findByOuterOrderNo(string $outerOrderNo): ?PointsExchangeOrder;

    /**
     * 查找用户的订单
     */
    public function findByBuyer(string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection;

    /**
     * 统计用户兑换次数
     */
    public function countByBuyerAndProduct(string $ownerType, string $ownerId, string $productId): int;
} 