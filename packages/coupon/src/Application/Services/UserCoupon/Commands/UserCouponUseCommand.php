<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class UserCouponUseCommand extends Data
{
    /**
     * 所有者信息
     */
    public UserInterface $owner;

    /**
     * 用户优惠券ID
     */
    public int $userCouponId;

    /**
     * 订单ID
     */
    public int $orderId;

    /**
     * 订单金额
     */
    public float $orderAmount;

    /**
     * 用户信息
     */
    public UserInterface $user;

    /**
     * 使用场景
     */
    public ?string $scene = null;

    /**
     * 扩展参数
     */
    public array $extra = [];
}