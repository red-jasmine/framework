<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories\Eloquent;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CouponRepository extends EloquentRepository implements CouponRepositoryInterface
{
    protected static string $eloquentModelClass = Coupon::class;
} 