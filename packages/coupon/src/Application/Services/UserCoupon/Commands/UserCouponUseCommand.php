<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Money\Data\Money;
use RedJasmine\Coupon\Domain\Data\UserCouponUseData;
use RedJasmine\Support\Data\Data;

class UserCouponUseCommand extends Data
{

    protected string $primaryKey = 'coupon_no';
    /**
     * 优惠券使用数据
     * @var UserCouponUseData[]
     */
    public array $usages = [];

}