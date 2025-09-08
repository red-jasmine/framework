<?php

namespace RedJasmine\Coupon\Infrastructure\Repositories\Eloquent;

use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Repositories\CouponRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CouponRepository extends Repository implements CouponRepositoryInterface
{
    protected static string $modelClass = Coupon::class;
} 