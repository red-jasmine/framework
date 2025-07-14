<?php

namespace RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class CouponUsageReadRepository extends QueryBuilderReadRepository implements CouponUsageReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = CouponUsage::class;

    /**
     * 过滤器
     * @return array
     */
    protected function allowedFilters(?Query $query = null) : array
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
     * 允许的排序字段
     * @return array
     */
    protected function allowedSorts(?Query $query = null) : array
    {
        return [
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('used_at'),
            AllowedSort::field('discount_amount'),
        ];
    }

    /**
     * 允许包含的关联
     * @return array
     */
    protected function allowedIncludes(?Query $query = null) : ?array
    {
        return ['coupon', 'userCoupon'];
    }
} 