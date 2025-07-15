<?php

namespace RedJasmine\Coupon\Domain\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Support\Domain\Policies\HasDefaultPolicy;

class CouponPolicy
{
    use HandlesAuthorization;

    public static function getModel() : string
    {
        return Coupon::class;
    }


    use HasDefaultPolicy;
}
