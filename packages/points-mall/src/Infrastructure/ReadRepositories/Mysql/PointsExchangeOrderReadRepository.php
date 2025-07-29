<?php

namespace RedJasmine\PointsMall\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\PointsMall\Domain\Models\PointsExchangeOrder;
use RedJasmine\PointsMall\Domain\Repositories\PointsExchangeOrderReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class PointsExchangeOrderReadRepository extends QueryBuilderReadRepository implements PointsExchangeOrderReadRepositoryInterface
{
    public static $modelClass = PointsExchangeOrder::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
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
     * 允许的排序字段配置
     */
    public function allowedSorts(): array
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
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            'pointProduct',
            'productSource',
        ];
    }

    /**
     * 根据订单号查找订单
     */
    public function findByOrderNo(string $orderNo): ?PointsExchangeOrder
    {
        return $this->query()
            ->where('order_no', $orderNo)
            ->first();
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
    public function findByBuyer(string $ownerType, string $ownerId): \Illuminate\Database\Eloquent\Collection
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
    public function findUserOrders(string $ownerType, string $ownerId, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where('owner_type', $ownerType)
            ->where('owner_id', $ownerId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
} 