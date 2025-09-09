<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 优惠券仓库接口
 *
 * 提供优惠券实体的读写操作统一接口
 *
 * @method Coupon find($id)
 */
interface CouponRepositoryInterface extends RepositoryInterface
{
    // 合并了原CouponReadRepositoryInterface的功能
    // 所有读写操作都通过统一接口提供
}
