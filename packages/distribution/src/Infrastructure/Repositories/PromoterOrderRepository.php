<?php

namespace RedJasmine\Distribution\Infrastructure\Repositories;

use RedJasmine\Distribution\Domain\Models\PromoterOrder;
use RedJasmine\Distribution\Domain\Repositories\PromoterOrderRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 推广员订单仓库实现
 *
 * 基于Repository实现，提供推广员订单实体的读写操作能力
 */
class PromoterOrderRepository extends Repository implements PromoterOrderRepositoryInterface
{
    protected static string $modelClass = PromoterOrder::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('promoter_id'),
            AllowedFilter::exact('order_id'),
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
}