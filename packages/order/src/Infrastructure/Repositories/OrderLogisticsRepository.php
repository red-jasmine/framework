<?php

namespace RedJasmine\Order\Infrastructure\Repositories;

use RedJasmine\Order\Domain\Models\OrderLogistics;
use RedJasmine\Order\Domain\Repositories\OrderLogisticsRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 订单物流仓库实现
 *
 * 基于Repository实现，提供订单物流实体的读写操作能力
 */
class OrderLogisticsRepository extends Repository implements OrderLogisticsRepositoryInterface
{
    protected static string $modelClass = OrderLogistics::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::exact('logistics_company_id'),
            AllowedFilter::exact('status'),
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
            'company',
        ];
    }
}
