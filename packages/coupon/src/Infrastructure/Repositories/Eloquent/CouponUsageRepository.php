<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories\Eloquent;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CouponUsageRepository extends Repository implements CouponUsageRepositoryInterface
{
    protected static string $modelClass = CouponUsage::class;
} 