<?php

namespace RedJasmine\Shopping\Infrastructure\Services;

use Cknow\Money\Money;
use Exception;
use InvalidArgumentException;
use RedJasmine\Coupon\Application\Services\UserCoupon\Commands\UserCouponUseCommand;
use RedJasmine\Coupon\Application\Services\UserCoupon\Queries\UserCouponPaginateQuery;
use RedJasmine\Coupon\Application\Services\UserCoupon\UserCouponApplicationService;
use RedJasmine\Coupon\Domain\Data\UserCouponUseData;
use RedJasmine\Coupon\Domain\Models\UserCoupon;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\OrdersData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Models\Enums\DiscountLevelEnum;
use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\CouponUsageData;
use RedJasmine\Support\Contracts\UserInterface;

class CouponServiceIntegration implements CouponServiceInterface
{


    public function __construct(
        protected UserCouponApplicationService $service,

    ) {
    }

    /**
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return array|CouponInfoData[]
     * @throws Exception
     */
    public function getUserCouponsByProduct(ProductPurchaseFactor $productPurchaseFactor) : array
    {


        return $this->getAvailableCoupons($productPurchaseFactor->buyer, DiscountLevelEnum::PRODUCT, $productPurchaseFactor);

    }

    /**
     * @param  OrderData  $orderData
     *
     * @return CouponInfoData[]
     * @throws Exception
     */
    public function getUserCouponsByOrder(OrderData $orderData) : array
    {
        return $this->getAvailableCoupons($orderData->buyer, DiscountLevelEnum::ORDER, $orderData);

    }

    /**
     * @param  UserInterface  $user
     * @param  DiscountLevelEnum  $discountLevel
     * @param  OrdersData|OrderData|ProductPurchaseFactor  $factor
     *
     * @return CouponInfoData[]
     * @throws Exception
     */
    protected function getAvailableCoupons(
        UserInterface $user,
        DiscountLevelEnum $discountLevel,
        OrdersData|OrderData|ProductPurchaseFactor $factor
    ) : array {
        $userCoupons = $this->getUserCoupons(
            $user,
            $discountLevel);

        $coupons = [];

        foreach ($userCoupons as $userCoupon) {
            $couponInfoData = $this->calculateDiscountAmount($userCoupon, $factor);
            if (bccomp($couponInfoData->discountAmount->getAmount(), 0, 2) > 0) {
                // 有优惠
                $coupons[] = $couponInfoData;
            }
        }
        // 获取当前所有可用优惠券
        return $coupons;
    }

    /**
     * @param  OrdersData  $ordersData
     *
     * @return CouponInfoData[]
     * @throws Exception
     */
    public function getUserCheckoutCoupons(OrdersData $ordersData) : array
    {
        return $this->getAvailableCoupons($ordersData->buyer, DiscountLevelEnum::CHECKOUT, $ordersData);
    }


    protected function calculateDiscountAmount(
        UserCoupon $userCoupon,
        OrdersData|OrderData|ProductPurchaseFactor $factor
    ) : CouponInfoData {
        $couponInfoData                = new CouponInfoData();
        $couponInfoData->couponId      = $userCoupon->coupon->id;
        $couponInfoData->label         = $userCoupon->coupon->label;
        $couponInfoData->couponNo      = $userCoupon->coupon_no;
        $couponInfoData->costBearer    = $userCoupon->coupon->cost_bearer;
        $couponInfoData->discountLevel = $userCoupon->discount_level;

        $couponInfoData->discountAmount = Money::parse(0);
        switch ($userCoupon->discount_level) {
            case DiscountLevelEnum::PRODUCT:
                if (($factor instanceof ProductPurchaseFactor) === false) {
                    throw new InvalidArgumentException('参数错误');
                }
                $productFactor = $factor;
                $money         = $productFactor->getProductInfo()->getProductAmountInfo()->totalPrice;
                $quantity      = $productFactor->quantity;
                if ($userCoupon->coupon->checkUsageRules($productFactor)) {
                    if ($userCoupon->coupon->isReachedThreshold($money, $quantity)) {
                        $couponInfoData->discountAmount = $userCoupon->coupon->calculateDiscountAmount($money);
                    }
                }
                break;
            case DiscountLevelEnum::ORDER:
                if (($factor instanceof OrderData) === false) {
                    throw new InvalidArgumentException('参数错误');
                }
                $orderData   = $factor;
                $money       = null;
                $quantity    = 0; // 数量
                $proportions = [];
                foreach ($orderData->products as $product) {
                    $proportions[$product->getSerialNumber()] = 0;
                    if ($userCoupon->coupon->checkUsageRules($product)) {
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
                if ($userCoupon->coupon->isReachedThreshold($money, $quantity)) {
                    $couponInfoData->discountAmount = $userCoupon->coupon->calculateDiscountAmount($money);
                }
                $couponInfoData->proportions = $proportions;
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
                        if ($userCoupon->coupon->checkUsageRules($product)) {
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

                if ($userCoupon->coupon->isReachedThreshold($money, $quantity)) {
                    $couponInfoData->discountAmount = $userCoupon->coupon->calculateDiscountAmount($money);
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
        $query                = new   UserCouponPaginateQuery;
        $query->user          = $user;
        $query->discountLevel = $discountLevel;
        $query->include       = ['coupon'];
        $query->perPage       = 1000;// TODO

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