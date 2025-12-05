<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 用户优惠券仓库接口
 *
 * 提供用户优惠券实体的读写操作统一接口
 *
 * @method UserCoupon find($id)
 */
interface UserCouponRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据优惠券编号查找
     */
    public function findByNo(string $no) : UserCoupon;

    /**
     * 根据优惠券编号查找（加锁）
     */
    public function findByNoLock(string $no) : UserCoupon;

    /**
     * 获取用户优惠券数量
     *
     */
    public function getUserCouponCountByCoupon(UserInterface $user, Coupon $coupon) : int;


}
