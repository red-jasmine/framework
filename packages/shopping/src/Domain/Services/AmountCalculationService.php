<?php

namespace RedJasmine\Shopping\Domain\Services;

use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\CouponServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Data\CouponInfoData;
use RedJasmine\Shopping\Domain\Data\OrderAmountData;
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
     * @param  ProductPurchaseFactor[]  $productPurchaseFactors
     *
     * @return OrderAmountData
     */
    protected function getOrderAmount(array $productPurchaseFactors) : OrderAmountData
    {


        // 商品基础流程

        $productPurchaseFactors = $this->init($productPurchaseFactors);

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
            $productAmount = $this->promotionService->getProductPromotion($productPurchaseFactor, $productInfo->getProductAmountInfo());

            // 查询商品可用的优惠券
            $productAmount->availableCoupons = $this->couponService->getUserCouponsByProduct($productPurchaseFactor);
            $productInfo->setProductAmountInfo($productAmount);
        }

        // 处理优惠券
        $productPurchaseFactors = $this->handleCoupons($productPurchaseFactors);
        $orderAmount            = new OrderAmountData(new Currency('CNY'));
        foreach ($productPurchaseFactors as $index => $productPurchaseFactor) {
            $orderAmount->products[$productPurchaseFactor->getKey() ?? $index] = $productPurchaseFactor->getProductInfo();
        }


        // 通过下单因子 TODO
        // 查询邮费信息 TODO
        // 查询订单优惠信息 TODO

        $orderAmount->calculate();


        return $orderAmount;
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
                continue;
            }
            // 生成序列号
            $productPurchaseFactor->buildSerialNumber();
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

    protected function handleCoupons(array $productPurchaseFactors) : array
    {

        // 匹配优惠券
        $productPurchaseFactors = $this->matchCoupons($productPurchaseFactors);

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

        return $productPurchaseFactors;

    }

    protected function matchCoupons(array &$productPurchaseFactors) : array
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

                        $this->matchCoupons($productPurchaseFactors);
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