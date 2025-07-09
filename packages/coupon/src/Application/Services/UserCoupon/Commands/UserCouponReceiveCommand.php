<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class UserCouponReceiveCommand extends Data
{
    /**
     * 所有者信息
     */
    public UserInterface $owner;

    /**
     * 优惠券ID
     */
    public int $couponId;

    /**
     * 用户信息
     */
    public UserInterface $user;

    /**
     * 领取渠道
     */
    public ?string $channel = null;

    /**
     * 邀请码
     */
    public ?string $inviteCode = null;

    /**
     * 扩展参数
     */
    public array $extra = [];
}