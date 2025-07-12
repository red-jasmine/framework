<?php

namespace RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\UserCouponReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class UserCouponReadRepository extends QueryBuilderReadRepository implements UserCouponReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = UserCoupon::class;


    /**
     * 过滤器
     *
     * @param  Query|null  $query
     *
     * @return array
     */
    protected function allowedFilters(?Query $query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('coupon_no'),
            AllowedFilter::exact('coupon_id'),
            AllowedFilter::exact('user_type'),
            AllowedFilter::exact('user_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::scope('userVisible'),
            AllowedFilter::scope('availableAt'),
            AllowedFilter::scope('usable'),
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
            AllowedSort::field('start_at'),
            AllowedSort::field('end_at'),
            AllowedSort::field('used_at'),
        ];
    }

    /**
     * 允许包含的关联
     * @return array
     */
    protected function allowedIncludes(?Query $query = null) : ?array
    {
        return ['coupon', 'usage'];
    }
} 