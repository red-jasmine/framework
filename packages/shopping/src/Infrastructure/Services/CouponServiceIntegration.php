<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Cknow\Money\Money;
use Exception;
use InvalidArgumentException;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Data\UserCouponUseData;
use RedJasmine\Coupon\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\CouponUsageData;
use RedJasmine\Shopping\Domain\Data\OrdersData;
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
            DiscountLevelEnum::PRODUCT);

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
            DiscountLevelEnum::ORDER);

        $coupons = [];
        // 优惠券逻辑验证
        // 是否满足门槛
        // 获取所有 满足门槛的优惠券
        foreach ($userCoupons as $userCoupon) {
            $canUseResult = $userCoupon->canUseOrder($orderData);
            if ($canUseResult->isCanUse) {
                // 获取最终实际优惠金额

                $discountAmount = $userCoupon->calculateDiscountAmount($canUseResult->amount);

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

    public function getUserCheckoutCoupons(OrdersData $ordersData) : array
    {
        // 获取用户优惠券
        $userCoupons = $this->getUserCoupons($ordersData->buyer, DiscountLevelEnum::CHECKOUT);


        $coupons = [];
        // 优惠券逻辑验证
        // 是否满足门槛
        // 获取所有 满足门槛的优惠券
        foreach ($userCoupons as $userCoupon) {
            // 多优惠券计算出订单金额
            $couponInfoData = $this->calculateDiscountAmount($userCoupon, $ordersData);
            if (bccomp($couponInfoData->discountAmount->getAmount(), 0, 2) > 0) {
                // 有优惠
                $coupons[] = $couponInfoData;
            }
        }
        // 获取当前所有可用优惠券
        return $coupons;
    }


    protected function calculateDiscountAmount(
        UserCoupon $userCoupon,
        OrdersData|OrderData|ProductPurchaseFactor $factor
    ) : CouponInfoData {
        $couponInfoData                 = new CouponInfoData();
        $couponInfoData->couponId       = $userCoupon->coupon->id;
        $couponInfoData->label          = $userCoupon->coupon->label;
        $couponInfoData->couponNo       = $userCoupon->coupon_no;
        $couponInfoData->costBearer     = $userCoupon->coupon->cost_bearer;
        $couponInfoData->discountLevel = $userCoupon->discount_level;

        $couponInfoData->discountAmount = Money::parse(0);
        switch ($userCoupon->discount_level) {
            case DiscountLevelEnum::PRODUCT:
                if (($factor instanceof ProductPurchaseFactor) === false) {
                    throw new InvalidArgumentException('参数错误');
                }
                break;
            case DiscountLevelEnum::ORDER:
                if (($factor instanceof OrderData) === false) {
                    throw new InvalidArgumentException('参数错误');
                }
                break;
            case DiscountLevelEnum::CHECKOUT:
                if (($factor instanceof OrdersData) === false) {
                    throw new InvalidArgumentException('参数错误');
                }
                $money       = null;
                $quantity    = 0; // 数量
                $ordersData  = $factor;
                $proportions = [];
                foreach ($ordersData->orders as $orderData) {
                    $proportions[$orderData->getSerialNumber()] = 0;
                    foreach ($orderData->products as $product) {
                        if ($userCoupon->isMeetRules($product)) {
                            $productAmount = $product->getProductInfo()->getProductAmountInfo()->getProductAmount();
                            $quantity      = $quantity + $product->quantity;
                            $money         = $money ? $money->add($productAmount) : $productAmount;

                            $proportions[$orderData->getSerialNumber()] =
                                bcadd(
                                    $proportions[$orderData->getSerialNumber()],
                                    $productAmount->getAmount(),
                                    2
                                );
                        }

                    }


                }

                if ($userCoupon->isReachedThreshold($money, $quantity)) {
                    $couponInfoData->discountAmount = $userCoupon->calculateDiscountAmount($money);
                }
                $couponInfoData->proportions = $proportions;

                break;
            case DiscountLevelEnum::SHIPPING:
                throw new Exception('To be implemented');
        }


        return $couponInfoData;
    }

    /**
     * 获取优惠券
     *
     * @param  UserInterface  $user
     * @param  DiscountLevelEnum  $discountLevel
     *
     * @return UserCoupon[]
     */
    protected function getUserCoupons(UserInterface $user, DiscountLevelEnum $discountLevel) : array
    {
        $query                 = new   UserCouponPaginateQuery;
        $query->user           = $user;
        $query->discountLevel = $discountLevel;
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