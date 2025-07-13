<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
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
            /**
             * @var UserCoupon $userCoupon
             */
            if ($userCoupon->canUse($productPurchaseFactor)) {
                // 获取最终实际优惠金额
                $discountAmount = $userCoupon->calculateDiscountAmount($productPurchaseFactor);

                if (bccomp($discountAmount->getAmount(), 0, 2) > 0) {
                    // 有优惠
                    $couponInfoData                 = new CouponInfoData();
                    $couponInfoData->couponId       = $userCoupon->coupon->id;
                    $couponInfoData->label          = $userCoupon->coupon->label;
                    $couponInfoData->couponNo       = $userCoupon->coupon_no;
                    $couponInfoData->costBearer     = $userCoupon->coupon->cost_bearer ?? $productPurchaseFactor->product->seller;
                    $couponInfoData->discountAmount = $discountAmount;
                    $coupons[]                      = $couponInfoData;
                }

            }
        }

        // 获取当前所有可用优惠券
        return $coupons;


    }

    protected function getUserCoupons(UserInterface $user
    ) : Paginator|LengthAwarePaginator {
        $query          = new   UserCouponPaginateQuery;
        $query->user    = $user;
        $query->include = ['coupon'];
        $query->perPage = 1000;// TODO

        return $this->service->paginate($query);


    }


    // 获取用户有效优惠券

    public function useCoupon(string $couponNo, string $orderNo) : bool
    {
        $command = new UserCouponUseCommand;
        $command->setKey($couponNo);
        $command->orderNo        = $orderNo;
        $command->discountAmount = null;
        return $this->service->use($command);
    }


}