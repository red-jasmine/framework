<?php

namespace RedJasmine\Order\Infrastructure\Repositories;

use RedJasmine\Order\Domain\Models\Refund;
use RedJasmine\Order\Domain\Repositories\RefundRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 退款仓库实现
 *
 * 基于Repository实现，提供退款实体的读写操作能力
 */
class RefundRepository extends Repository implements RefundRepositoryInterface
{
    protected static string $modelClass = Refund::class;

    public function findByNo(string $no): Refund
    {
        return Refund::where('refund_no', $no)->firstOrFail();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('refund_no'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::partial('search', 'refund_no'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('refund_no'),
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
            'items',
        ];
    }
}
