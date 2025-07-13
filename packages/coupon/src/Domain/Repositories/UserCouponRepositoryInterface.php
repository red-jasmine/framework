<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method UserCoupon find($id)
 */
interface UserCouponRepositoryInterface extends RepositoryInterface
{
    // 可添加特定的写操作方法

    public function findByNo(string $no) : UserCoupon;
} 