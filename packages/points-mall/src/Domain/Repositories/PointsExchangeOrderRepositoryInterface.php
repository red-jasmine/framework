<?php

namespace RedJasmine\PointsMall\Domain\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 积分兑换订单仓库接口
 *
 * 提供积分兑换订单实体的读写操作统一接口
 *
 * @method PointsExchangeOrder find($id)
 */
interface PointsExchangeOrderRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据订单号查找订单
     */
    public function findByNo(string $no): ?PointsExchangeOrder;

    /**
     * 根据订单号查找订单（别名方法）
     */
    public function findByOrderNo(string $orderNo): ?PointsExchangeOrder;

    /**
     * 根据关联订单号查找订单
     */
    public function findByOuterOrderNo(string $outerOrderNo): ?PointsExchangeOrder;

    /**
     * 查找用户的订单
     */
    public function findByBuyer(string $ownerType, string $ownerId): Collection;

    /**
     * 统计用户兑换次数
     */
    public function countByBuyerAndProduct(string $ownerType, string $ownerId, string $productId): int;

    /**
     * 查找订单及其商品信息
     */
    public function findWithProduct(string $orderId): ?PointsExchangeOrder;

    /**
     * 查找用户的订单列表
     */
    public function findUserOrders(string $ownerType, string $ownerId, int $limit = 20): Collection;
} 