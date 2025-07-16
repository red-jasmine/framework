<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

interface UserCouponReadRepositoryInterface extends ReadRepositoryInterface
{
    // 可添加特定的查询方法

    /**
     * 获取用户优惠券数量
     *
     * @param  UserInterface  $user
     * @param  Coupon  $coupon
     *
     * @return int
     */
    public function getUserCouponCountByCoupon(UserInterface $user, Coupon $coupon) : int;
} 