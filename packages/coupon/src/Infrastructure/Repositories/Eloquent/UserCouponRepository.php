<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories\Eloquent;

use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Domain\Repositories\UserCouponRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class UserCouponRepository extends Repository implements UserCouponRepositoryInterface
{
    protected static string $modelClass = UserCoupon::class;

    public function findByNo(string $no) : UserCoupon
    {
        return static::$modelClass::where('coupon_no', $no)->firstOrFail();
    }

    public function findByNoLock(string $no) : UserCoupon
    {
        return static::$modelClass::where('coupon_no', $no)
                                  ->lockForUpdate()
                                  ->firstOrFail();
    }


} 