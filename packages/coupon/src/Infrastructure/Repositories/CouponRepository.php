<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 优惠券仓库实现
 *
 * 基于Repository实现，提供优惠券实体的读写操作能力
 */
class CouponRepository extends Repository implements CouponRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Coupon::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
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
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
            AllowedSort::field('start_at'),
            AllowedSort::field('end_at'),
            AllowedSort::field('sort'),
        ];
    }

   protected function allowedIncludes(?Query $query = null) : array
   {
       return ['issueStat', 'userCoupons'];
   }
}
