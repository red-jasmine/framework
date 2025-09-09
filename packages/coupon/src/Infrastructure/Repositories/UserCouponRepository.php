<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 用户优惠券仓库实现
 *
 * 基于Repository实现，提供用户优惠券实体的读写操作能力
 */
class UserCouponRepository extends Repository implements UserCouponRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = UserCoupon::class;

    /**
     * 根据优惠券编号查找
     */
    public function findByNo(string $no) : UserCoupon
    {
        return static::$modelClass::where('coupon_no', $no)->firstOrFail();
    }

    /**
     * 根据优惠券编号查找（加锁）
     */
    public function findByNoLock(string $no) : UserCoupon
    {
        return static::$modelClass::where('coupon_no', $no)
                                  ->lockForUpdate()
                                  ->firstOrFail();
    }

    /**
     * 获取用户优惠券数量
     */
    public function getUserCouponCountByCoupon(UserInterface $user, Coupon $coupon) : int
    {
        return $this->query()
                    ->where([
                        'user_type' => $user->getType(),
                        'user_id'   => $user->getKey(),
                        'coupon_id' => $coupon->id
                    ])->count();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
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
            AllowedFilter::scope('available'),
            AllowedFilter::scope('availableAt'),
            AllowedFilter::scope('usable'),
            AllowedFilter::exact('discount_level'),
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
            AllowedSort::field('start_at'),
            AllowedSort::field('end_at'),
            AllowedSort::field('used_at'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : ?array
    {
        return ['coupon', 'usage'];
    }
}
