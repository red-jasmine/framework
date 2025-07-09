<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * @method CouponUsage find($id)
 */
interface CouponUsageRepositoryInterface extends RepositoryInterface
{
    // 可添加特定的写操作方法
} 