<?php

namespace RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Repositories\CouponReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class CouponReadRepository extends QueryBuilderReadRepository implements CouponReadRepositoryInterface
{
    /**
     * @var $modelClass class-string
     */
    protected static string $modelClass = Coupon::class;

    /**
     * 过滤器
     * @return array
     */
    protected function allowedFilters(?Query $query = null): array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('discount_type'),
            AllowedFilter::exact('threshold_type'),
            AllowedFilter::exact('validity_type'),
            AllowedFilter::exact('issue_strategy'),
            AllowedFilter::partial('title'),
            AllowedFilter::partial('description'),
            AllowedFilter::scope('expiredAt'),
            AllowedFilter::scope('availableAt'),
        ];
    }

    /**
     * 允许的排序字段
     * @return array
     */
    protected function allowedSorts(?Query $query = null): array
    {
        return [
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('start_at'),
            AllowedSort::field('end_at'),
            AllowedSort::field('sort'),
        ];
    }

    /**
     * 允许包含的关联
     * @return array
     */
    protected function allowedIncludes(?Query $query = null): ?array
    {
        return ['issueStat', 'userCoupons'];
    }
} 