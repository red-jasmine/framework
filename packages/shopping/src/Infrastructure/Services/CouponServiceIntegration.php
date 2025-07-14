<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Data\UserCouponUseData;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountTargetEnum;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\CouponUsageData;
use RedJasmine\Support\Contracts\UserInterface;

class CouponServiceIntegration implements CouponServiceInterface
{
    protected array $userCoupons = [];

    public function __construct(
        protected UserCouponApplicationService $service,

    ) {
    }

    /**
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return array|CouponInfoData[]
     */
    public function getUserCouponsByProduct(ProductPurchaseFactor $productPurchaseFactor) : array
    {
        // 获取用户优惠券
        $userCoupons = $this->getUserCoupons(
            $productPurchaseFactor->buyer,
            DiscountTargetEnum::PRODUCT_AMOUNT);

        $coupons = [];
        // 优惠券逻辑验证
        // 是否满足门槛
        // 获取所有 满足门槛的优惠券
        foreach ($userCoupons as $userCoupon) {
            if ($userCoupon->canUse($productPurchaseFactor)) {
                // 获取最终实际优惠金额
                $discountAmount = $userCoupon->calculateDiscountAmount(
                    $productPurchaseFactor->getProductInfo()->getProductAmountInfo()->totalPrice
                );

                if (bccomp($discountAmount->getAmount(), 0, 2) > 0) {
                    // 有优惠
                    $couponInfoData                 = new CouponInfoData();
                    $couponInfoData->couponId       = $userCoupon->coupon->id;
                    $couponInfoData->label          = $userCoupon->coupon->label;
                    $couponInfoData->couponNo       = $userCoupon->coupon_no;
                    $couponInfoData->costBearer     = $userCoupon->coupon->cost_bearer;
                    $couponInfoData->discountAmount = $discountAmount;
                    $coupons[]                      = $couponInfoData;
                }

            }
        }

        // 获取当前所有可用优惠券
        return $coupons;


    }

    public function getUserCouponsByOrder(OrderData $orderData) : array
    {
        // 获取用户优惠券 ,订单级别
        $userCoupons = $this->getUserCoupons(
            $orderData->buyer,
            DiscountTargetEnum::ORDER_AMOUNT);

        $coupons = [];
        // 优惠券逻辑验证
        // 是否满足门槛
        // 获取所有 满足门槛的优惠券
        foreach ($userCoupons as $userCoupon) {
            $isCanUse = $userCoupon->canUseOrder($orderData);
            if ($isCanUse['is_can_use']) {
                // 获取最终实际优惠金额

                $discountAmount = $userCoupon->calculateDiscountAmount($isCanUse['amount']);

                if (bccomp($discountAmount->getAmount(), 0, 2) > 0) {
                    // 有优惠
                    $couponInfoData                 = new CouponInfoData();
                    $couponInfoData->couponId       = $userCoupon->coupon->id;
                    $couponInfoData->label          = $userCoupon->coupon->label;
                    $couponInfoData->couponNo       = $userCoupon->coupon_no;
                    $couponInfoData->costBearer     = $userCoupon->coupon->cost_bearer;
                    $couponInfoData->discountAmount = $discountAmount;
                    $coupons[]                      = $couponInfoData;
                }

            }
        }
        // 获取当前所有可用优惠券
        return $coupons;
    }


    /**
     * 获取优惠券
     *
     * @param  UserInterface  $user
     * @param  DiscountTargetEnum  $discountTarget
     *
     * @return UserCoupon[]
     */
    protected function getUserCoupons(UserInterface $user, DiscountTargetEnum $discountTarget) : array
    {
        $query                 = new   UserCouponPaginateQuery;
        $query->user           = $user;
        $query->discountTarget = $discountTarget;
        $query->include        = ['coupon'];
        $query->perPage        = 1000;// TODO

        return $this->service->paginate($query)->items();


    }

    /**
     * @param  string  $couponNo
     * @param  \RedJasmine\Ecommerce\Domain\Data\Coupon\CouponUsageData[]  $usages
     *
     * @return bool
     */
    public function useCoupon(string $couponNo, array $usages) : bool
    {
        $command = new UserCouponUseCommand;
        $command->setKey($couponNo);
        $userCouponUseDatas = [];
        foreach ($usages as $usage) {

            $userCouponUseData                 = new UserCouponUseData();
            $userCouponUseData->discountAmount = $usage->discountAmount;
            $userCouponUseData->orderType      = $usage->orderType;
            $userCouponUseData->orderNo        = $usage->orderNo;
            $userCouponUseData->orderProductNo = $usage->orderProductNo;
            $userCouponUseDatas[]              = $userCouponUseData;

        }
        $command->usages = $userCouponUseDatas;
        return $this->service->use($command);
    }


}