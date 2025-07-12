<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Illuminate\Foundation\Auth\User;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Data\CouponInfoData;
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
        $userCouponPaginate = $this->getUserCoupons($productPurchaseFactor->buyer);

        $coupons = [];
        // 优惠券逻辑验证
        // 是否满足门槛
        // 获取所有 满足门槛的优惠券
        foreach ($userCouponPaginate->items() as $userCoupon) {
            // TODO 判断叠加，不能使用多张
            /**
             * @var UserCoupon $userCoupon
             */
            if ($userCoupon->canUse($productPurchaseFactor)) {
                // 获取最终实际优惠金额
                $discountAmount = $userCoupon->calculateDiscountAmount($productPurchaseFactor);

                if (bccomp($discountAmount->getAmount(), 0, 2) > 0) {
                    // 有优惠
                    $couponInfoData                 = new CouponInfoData();
                    $couponInfoData->couponNo       = $userCoupon->coupon_no;
                    $couponInfoData->discountAmount = $discountAmount;
                    $couponInfoData->costBearer     = $userCoupon->coupon->cost_bearer ?? $productPurchaseFactor->product->seller;

                    $coupons[] = $couponInfoData;
                }

            }
        }

        $useCoupon = null;
        foreach ($coupons as $coupon) {
            if (!$useCoupon) {
                $useCoupon = $coupon;
            } else {
                if ($coupon->discountAmount->getAmount() > $useCoupon->discountAmount->getAmount()) {
                    $useCoupon = $coupon;
                }
            }
            // 获取优惠金额最大的优惠券
        }

        return [$useCoupon];

    }

    // 获取用户有效优惠券
    public function getUserCoupons(UserInterface $user)
    {
        $query          = new   UserCouponPaginateQuery;
        $query->user    = $user;
        $query->include = ['coupon'];
        $query->perPage = 1000;// TODO

        return $this->service->paginate($query);


    }

    protected function convertCouponInfo(UserCoupon $userCoupon)
    {

    }


}