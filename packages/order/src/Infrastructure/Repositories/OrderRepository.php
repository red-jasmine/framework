<?php

namespace RedJasmine\Order\Infrastructure\Repositories;

use RedJasmine\Order\Domain\Models\Order;
use RedJasmine\Order\Domain\Repositories\OrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 订单仓库实现
 *
 * 基于Repository实现，提供订单实体的读写操作能力
 */
class OrderRepository extends Repository implements OrderRepositoryInterface
{
    protected static string $modelClass = Order::class;

    public function findByNo(string $no): Order
    {
        return Order::where('order_no', $no)->firstOrFail();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('order_no'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::partial('search', 'order_no'),
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
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            'items',
            'logistics',
            'payments',
        ];
    }
}
