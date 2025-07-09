<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories\Eloquent;

use RedJasmine\Coupon\Domain\Models\CouponUsage;
use RedJasmine\Coupon\Domain\Repositories\CouponUsageRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CouponUsageRepository extends EloquentRepository implements CouponUsageRepositoryInterface
{
    protected static string $eloquentModelClass = CouponUsage::class;
} 