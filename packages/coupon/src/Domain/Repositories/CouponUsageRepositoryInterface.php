<?php

namespace RedJasmine\Coupon\Domain\Repositories;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 优惠券使用记录仓库接口
 *
 * 提供优惠券使用记录实体的读写操作统一接口
 *
 * @method CouponUsage find($id)
 */
interface CouponUsageRepositoryInterface extends RepositoryInterface
{

}
