<?php

namespace RedJasmine\Shopping\Domain\Services;

use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\Coupon\CouponInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderAmountInfoData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Hooks\ShoppingOrderProductAmountHook;
use RedJasmine\Shopping\Domain\Hooks\ShoppingOrderSplitProductHook;
use RedJasmine\Support\Foundation\Service\Service;

/**
 * 金额计算服务
 */
class AmountCalculationService extends Service
{


    public function __construct(
        protected ProductServiceInterface $productService,
        protected StockServiceInterface $stockService,
        protected PromotionServiceInterface $promotionService,
        protected OrderServiceInterface $orderService,
        protected CouponServiceInterface $couponService,
    ) {

    }


    /**
     * 计算订单基恩
     *
     * @param  OrderData  $orderData
     *
     * @return OrderData
     */
    protected function calculateOrderAmount(OrderData $orderData) : OrderData
    {

        $productPurchaseFactors = $orderData->products;

        // 商品基础流程

        $productPurchaseFactors = $this->init($productPurchaseFactors);
        $orderAmountInfo        = new OrderAmountInfoData(new Currency('CNY'));
        $orderData->setOrderAmountInfo($orderAmountInfo);
        // 通过购买商品因子
        foreach ($productPurchaseFactors as $productPurchaseFactor) {
            $productInfo = $productPurchaseFactor->getProductInfo();
            // 查询库存信息
            $productInfo->setStockInfo(
                $this->stockService->getStockInfo($productPurchaseFactor->product, $productPurchaseFactor->quantity)
            );
            // 获取商品金额信息

            $productInfo->setProductAmountInfo(
                ShoppingOrderProductAmountHook::hook($productPurchaseFactor,
                    fn() => $this->productService->getProductAmount($productPurchaseFactor)
                )
            );
            // 查询商品优惠信息
            $productAmountInfo = $this->promotionService->getProductPromotion($productPurchaseFactor, $productInfo->getProductAmountInfo());

            // 查询商品可用的优惠券
            $productAmountInfo->availableCoupons = $this->couponService->getUserCouponsByProduct($productPurchaseFactor);
            $productInfo->setProductAmountInfo($productAmountInfo);

            $orderAmountInfo->productAmountInfos[] = $productAmountInfo;
        }

        // 设置订单金额 的货币


        // 处理优惠券
        $this->handleCoupons($orderData);

        // 处理订单级别优惠券

        $orderData->getOrderAmountInfo()->calculate();


        return $orderData;
    }

    /**
     * @param  array  $productPurchaseFactors
     *
     * @return array|ProductPurchaseFactor[]
     */
    protected function init(array $productPurchaseFactors) : array
    {


        foreach ($productPurchaseFactors as $productPurchaseFactor) {
            /**
             * @var ProductPurchaseFactor $productPurchaseFactor
             */
            if (!$productPurchaseFactor->getSerialNumber()) {
                // 生成序列号
                $productPurchaseFactor->buildSerialNumber();
            }

            // 获取商品信息
            $productPurchaseFactor->setProductInfo(
                $this->productService->getProductInfo($productPurchaseFactor)
            );
            // 获取商品拆单信息
            $productPurchaseFactor->getProductInfo()->setSplitKey(
                ShoppingOrderSplitProductHook::hook(
                    $productPurchaseFactor,
                    fn() => $this->orderService->getOrderProductSplitKey($productPurchaseFactor)
                )
            );
        }

        return $productPurchaseFactors;
    }

    protected function handleCoupons(OrderData $orderData) : OrderData
    {

        $this->handleProductCoupons($orderData);

        $this->handleOrderCoupons($orderData);

        return $orderData;

    }


    protected function handleProductCoupons(OrderData $orderData) : void
    {
        // 匹配优惠券
        $productPurchaseFactors = $this->matchBestProductCoupons($orderData->products);

        // 计算商品优惠
        foreach ($productPurchaseFactors as $productPurchaseFactor) {
            /**
             * @var ProductPurchaseFactor $productPurchaseFactor
             */
            /**
             * @var CouponInfoData $couponInfoData
             */
            foreach ($productPurchaseFactor->getProductInfo()->getProductAmountInfo()->coupons as $couponInfoData) {
                $productPurchaseFactor
                    ->getProductInfo()
                    ->getProductAmountInfo()
                    ->discountAmount =
                    $productPurchaseFactor
                        ->getProductInfo()
                        ->getProductAmountInfo()
                        ->discountAmount
                        ->add($couponInfoData->discountAmount);
            }


        }
    }

    protected function handleOrderCoupons(OrderData $orderData) : void
    {
        // 获取订单级可用优惠券
        $orderAmountInfo = $orderData->getOrderAmountInfo();

        $orderAmountInfo->availableCoupons = $this->couponService->getUserCouponsByOrder($orderData);

        // 匹配做好优惠券
        $this->matchBestOrderCoupons($orderData);


    }

    protected function matchBestOrderCoupons(OrderData $orderData) : void
    {
        $orderAmountInfo = $orderData->getOrderAmountInfo();
        // 对优惠券进行排序
        $availableCoupons = collect($orderAmountInfo->availableCoupons)
            ->sort(function ($a, $b) {
                return $b->discountAmount->getAmount() <=> $a->discountAmount->getAmount();
            });

        if (count($availableCoupons) > 0) {
            // 订单级别优惠券
            $bestCoupon                      = $availableCoupons->first();
            $orderAmountInfo->coupons[]      = $bestCoupon;
            $orderAmountInfo->discountAmount = $bestCoupon->discountAmount;

        }
    }

    // 匹配 最薄
    protected function matchBestProductCoupons(array &$productPurchaseFactors) : array
    {
        // 最优优惠券
        $bestCoupons = [];


        foreach ($productPurchaseFactors as $productPurchaseFactor) {
            /**
             * @var ProductPurchaseFactor $productPurchaseFactor
             */
            $availableProductCoupons = $productPurchaseFactor->getProductInfo()->getProductAmountInfo()->availableCoupons;
            if (count($availableProductCoupons) <= 0) {
                continue;
            }
            // 对金额进行排序排序
            $availableProductCoupons = collect($availableProductCoupons)->sort(function ($a, $b) {
                return $b->discountAmount->getAmount() <=> $a->discountAmount->getAmount();
            });


            // 选出最优优惠券
            /**
             * @var CouponInfoData $availableProductCoupon
             */
            foreach ($availableProductCoupons as $availableProductCoupon) {
                if (isset($bestCoupons[$availableProductCoupon->couponNo])) {
                    if ($bestCoupons[$availableProductCoupon->couponNo]['product']->getSerialNumber() === $productPurchaseFactor->getSerialNumber()) {
                        // 如果是已有优惠券和当前商品优惠券是同一个
                        continue;
                    }
                    // 对比 已有最优优惠券中的金额
                    if ($bestCoupons[$availableProductCoupon->couponNo]['amount']->getAmount()
                        < $availableProductCoupon->discountAmount->getAmount()) {
                        // 更新最优优惠券
                        $bestCoupons[$availableProductCoupon->couponNo]['product']
                            ->getProductInfo()
                            ->getProductAmountInfo()
                            ->coupons[] = [];

                        $bestCoupons[$availableProductCoupon->couponNo] = [
                            'amount'  => $availableProductCoupon->discountAmount,
                            'product' => $productPurchaseFactor,
                        ];


                        $productPurchaseFactor->getProductInfo()->getProductAmountInfo()->coupons[] = $availableProductCoupon;

                        $this->matchBestProductCoupons($productPurchaseFactors);
                        break;
                    }

                } else {
                    $bestCoupons[$availableProductCoupon->couponNo]                             = [
                        'amount'  => $availableProductCoupon->discountAmount,
                        'product' => $productPurchaseFactor,
                    ];
                    $productPurchaseFactor->getProductInfo()->getProductAmountInfo()->coupons[] = $availableProductCoupon;
                    break;
                }

            }


        }
        // 同一个优惠券 只能使用在一个产品中，一个优惠券不能使用多个产品中

        return $productPurchaseFactors;
    }


}