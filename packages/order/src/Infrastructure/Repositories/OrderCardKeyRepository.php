<?php

namespace RedJasmine\Order\Infrastructure\Repositories;

use RedJasmine\Order\Domain\Models\OrderCardKey;
use RedJasmine\Order\Domain\Repositories\OrderCardKeyRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 订单卡密仓库实现
 *
 * 基于Repository实现，提供订单卡密实体的读写操作能力
 */
class OrderCardKeyRepository extends Repository implements OrderCardKeyRepositoryInterface
{
    protected static string $modelClass = OrderCardKey::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::exact('order_product_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('content_type'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
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
            'order',
            'orderProduct',
        ];
    }
}
