<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories\Eloquent;

use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class PointsExchangeOrderRepository extends EloquentRepository implements PointsExchangeOrderRepositoryInterface
{
    /**
     * @var $eloquentModelClass class-string
     */
    protected static string $eloquentModelClass = PointsExchangeOrder::class;

    /**
     * 根据订单号查找订单
     */
    public function findByOrderNo(string $orderNo): ?PointsExchangeOrder
    {
        return static::$eloquentModelClass::where('order_no', $orderNo)->first();
    }

    /**
     * 根据关联订单号查找订单
     */
    public function findByOuterOrderNo(string $outerOrderNo): PointsExchangeOrder
    {
        return static::$eloquentModelClass::where('outer_order_no', $outerOrderNo)->firstOrFail();
    }

    /**
     * 查找用户的订单
     */
    public function findByBuyer(string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection
    {
        return static::$eloquentModelClass::where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 统计用户兑换次数
     */
    public function countByBuyerAndProduct(string $ownerType, string $ownerId, string $productId): int
    {
        return static::$eloquentModelClass::where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->where('point_product_id', $productId)
            ->count();
    }
} 