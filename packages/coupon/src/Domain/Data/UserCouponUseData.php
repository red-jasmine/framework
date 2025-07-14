<?php

namespace RedJasmine\Coupon\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

/**
 * 用户优惠券使用数据
 */
class UserCouponUseData extends Data
{
    /**
     * 订单ID
     */
    public string $orderType;
    /**
     * 订单ID
     */
    public string $orderNo;

    /**
     * 订单商品项ID
     */
    public ?string $orderProductNo = null;

    /**
     * 实际优惠金额
     */
    public Money $discountAmount;
}