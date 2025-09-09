<?php

namespace RedJasmine\Order\Infrastructure\Repositories;

use RedJasmine\Order\Domain\Models\OrderPayment;
use RedJasmine\Order\Domain\Repositories\OrderPaymentRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 订单支付仓库实现
 *
 * 基于Repository实现，提供订单支付实体的读写操作能力
 */
class OrderPaymentRepository extends Repository implements OrderPaymentRepositoryInterface
{
    protected static string $modelClass = OrderPayment::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::exact('payment_method'),
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
        ];
    }
}
