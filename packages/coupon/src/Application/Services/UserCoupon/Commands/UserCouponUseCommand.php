<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

class UserCouponUseCommand extends Data
{

    protected string $primaryKey = 'coupon_no';
    /**
     * 订单ID
     */
    public string $orderNo;

    /**
     * 订单金额
     */
    public ?Money $discountAmount = null;


    /**
     * 使用场景
     */
    public ?string $scene = null;

    /**
     * 扩展参数
     */
    public array $extra = [];
}