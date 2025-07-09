<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method Coupon find($id)
 */
interface CouponRepositoryInterface extends RepositoryInterface
{
    // 可添加特定的写操作方法
} 