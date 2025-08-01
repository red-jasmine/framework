<?php

namespace RedJasmine\Coupon\Infrastructure\ReadRepositories\Mysql;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\UserCouponReadRepositoryInterface;
use RedJasmine\Support\Contracts\UserInterface;
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
     * 获取用户优惠券数量
     *
     * @param  UserInterface  $user
     * @param  Coupon  $coupon
     *
     * @return int
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
            AllowedFilter::scope('available'),
            AllowedFilter::scope('availableAt'),
            AllowedFilter::scope('usable'),
            AllowedFilter::exact('discount_level'),
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