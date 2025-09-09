<?php

namespace RedJasmine\PointsMall\Infrastructure\Repositories;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 积分兑换订单仓库实现
 *
 * 基于Repository实现，提供积分兑换订单实体的读写操作能力
 */
class PointsExchangeOrderRepository extends Repository implements PointsExchangeOrderRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = PointsExchangeOrder::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('order_no'),
            AllowedFilter::exact('outer_order_no'),
            AllowedFilter::exact('point_product_id'),
            AllowedFilter::exact('product_type'),
            AllowedFilter::exact('product_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('payment_mode'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('order_no'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('exchange_time'),
            AllowedSort::field('point'),
            AllowedSort::field('price_amount'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'pointProduct',
            'productSource',
        ];
    }

    /**
     * 根据订单号查找订单
     */
    public function findByNo(string $no): ?PointsExchangeOrder
    {
        return $this->query()
            ->where('order_no', $no)
            ->first();
    }

    /**
     * 根据订单号查找订单（别名方法）
     */
    public function findByOrderNo(string $orderNo): ?PointsExchangeOrder
    {
        return $this->findByNo($orderNo);
    }

    /**
     * 根据关联订单号查找订单
     */
    public function findByOuterOrderNo(string $outerOrderNo): ?PointsExchangeOrder
    {
        return $this->query()
            ->where('outer_order_no', $outerOrderNo)
            ->first();
    }

    /**
     * 查找用户的订单
     */
    public function findByBuyer(string $ownerType, string $ownerId): Collection
    {
        return $this->query()
            ->where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 统计用户兑换次数
     */
    public function countByBuyerAndProduct(string $ownerType, string $ownerId, string $productId): int
    {
        return $this->query()
            ->where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->where('point_product_id', $productId)
            ->count();
    }

    /**
     * 查找订单及其商品信息
     */
    public function findWithProduct(string $orderId): ?PointsExchangeOrder
    {
        return $this->query()
            ->with('pointProduct')
            ->where('id', $orderId)
            ->first();
    }

    /**
     * 查找用户的订单列表
     */
    public function findUserOrders(string $ownerType, string $ownerId, int $limit = 20): Collection
    {
        return $this->query()
            ->where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
