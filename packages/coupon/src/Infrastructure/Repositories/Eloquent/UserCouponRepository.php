<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories\Eloquent;

use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class UserCouponRepository extends EloquentRepository implements UserCouponRepositoryInterface
{
    protected static string $eloquentModelClass = UserCoupon::class;

    public function findByNo(string $no) : UserCoupon
    {
        return static::$eloquentModelClass::where('coupon_no', $no)->firstOrFail();
    }

    public function findByNoLock(string $no) : UserCoupon
    {
        return static::$eloquentModelClass::where('coupon_no', $no)
                                          ->lockForUpdate()
                                          ->firstOrFail();
    }


} 