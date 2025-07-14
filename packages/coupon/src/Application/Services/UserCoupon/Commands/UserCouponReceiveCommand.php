<?php

namespace RedJasmine\Coupon\Application\Services\UserCoupon\Commands;

use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

class UserCouponReceiveCommand extends Data
{

    /**
     * 用户信息
     */
    public UserInterface $user;

    /**
     * 领取渠道
     */
    public ?string $channel = null;


    /**
     * 扩展参数
     */
    public array $extra = [];
}