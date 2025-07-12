<?php

namespace RedJasmine\Coupon\Domain\Services;

use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\Enums\UserCouponStatusEnum;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Coupon\Exceptions\CouponException;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Service\Service;

class CouponUserService extends Service
{

    /**
     * 领取优惠券
     *
     * @param  Coupon  $coupon
     * @param  UserInterface  $user
     *
     * @return UserCoupon
     * @throws CouponException
     */
    public function receive(Coupon $coupon, UserInterface $user) : UserCoupon
    {
        // 验证优惠券是否可以发放
        if (!$coupon->canIssue()) {
            throw new CouponException('优惠券不支持发放');
        }

        // TODO 验证领取条件


        [$validityStartTime, $validityEndTime] = $coupon->getValidityTimes();


        // 增加一次领域记录
        $coupon->increment('total_issued');
        // 构建 用户优惠券
        /**
         * @var UserCoupon $userCoupon
         */
        $userCoupon                      = UserCoupon::make([
            'owner' => $coupon->owner,
            'user'  => $user,
        ]);
        $userCoupon->coupon_id           = $coupon->id;
        $userCoupon->issue_time          = Carbon::now();
        $userCoupon->validity_start_time = $validityStartTime;
        $userCoupon->validity_end_time   = $validityEndTime;
        $userCoupon->status              = UserCouponStatusEnum::AVAILABLE;

        return $userCoupon;
    }

}