<?php

namespace RedJasmine\Coupon\Domain\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use RedJasmine\Coupon\Domain\Data\UserCouponUseData;
use RedJasmine\Coupon\Domain\Models\Coupon;
use RedJasmine\Coupon\Domain\Models\CouponUsage;
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


        [$validityStartTime, $validityEndTime] = $coupon->buildUserCouponValidityTimes();


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
        $userCoupon->discount_level     = $coupon->discount_level;
        $userCoupon->issue_time          = Carbon::now();
        $userCoupon->validity_start_time = $validityStartTime;
        $userCoupon->validity_end_time   = $validityEndTime;
        $userCoupon->status              = UserCouponStatusEnum::AVAILABLE;

        return $userCoupon;
    }


    /**
     * @param  UserCoupon  $userCoupon
     * @param  array  $usages
     *
     * @return true
     * @throws CouponException
     */
    public function use(UserCoupon $userCoupon, array $usages) : true
    {
        // 验证优惠券的是否可用
        if (!$userCoupon->isAvailable()) {
            throw new CouponException('优惠券不可用');
        }
        // 标记为已使用
        $userCoupon->use();
        // 添加使用记录
        $this->addUsages($userCoupon, $usages);

        return true;
    }

    /**
     * @param  UserCoupon  $userCoupon
     * @param  UserCouponUseData[]  $usages
     *
     * @return void
     */
    protected function addUsages(UserCoupon $userCoupon, array $usages) : void
    {
        $userCoupon->setRelation('usages', Collection::make());
        $userCoupon->coupon;
        foreach ($usages as $usage) {
            /**
             * @var UserCouponUseData $usage
             */
            $couponUsage                   = CouponUsage::make();
            $couponUsage->coupon_no        = $userCoupon->coupon_no;
            $couponUsage->coupon_id        = $userCoupon->coupon_id;
            $couponUsage->owner            = $userCoupon->owner;
            $couponUsage->user             = $userCoupon->user;
            $couponUsage->cost_bearer      = $userCoupon->coupon->cost_bearer;
            $couponUsage->order_type       = $usage->orderType;
            $couponUsage->order_no         = $usage->orderNo;
            $couponUsage->order_product_no = $usage->orderProductNo;
            $couponUsage->discount_amount  = $usage->discountAmount;
            $couponUsage->used_at          = Carbon::now();
            $userCoupon->usages->add($couponUsage);
        }

    }

}