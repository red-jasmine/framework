<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 优惠券使用记录仓库实现
 *
 * 基于Repository实现，提供优惠券使用记录实体的读写操作能力
 */
class CouponUsageRepository extends Repository implements CouponUsageRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = CouponUsage::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('coupon_id'),
            AllowedFilter::exact('user_coupon_id'),
            AllowedFilter::exact('user_type'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::exact('order_type'),
            AllowedFilter::exact('order_no'),
            AllowedFilter::exact('cost_bearer_type'),
            AllowedFilter::exact('cost_bearer_id'),

            AllowedFilter::callback('used_at_between', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween('used_at', $value);
                }
            }),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
    {
        return [
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('used_at'),
            AllowedSort::field('discount_amount'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : ?array
    {
        return ['coupon', 'userCoupon'];
    }
}
