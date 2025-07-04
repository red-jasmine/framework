<?php

namespace RedJasmine\Shopping\Domain\Services;

use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\OrderServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
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
    ) {

    }

    /**
     * @param  array|ProductPurchaseFactor[]  $productPurchaseFactors
     *
     * @return OrderAmountData
     */
    protected function getOrderAmount(array $productPurchaseFactors) : OrderAmountData
    {
        $orderAmount = new OrderAmountData(new Currency('CNY'));

        // 通过购买商品因子
        foreach ($productPurchaseFactors as $index => $productPurchaseFactor) {
            // 获取商品信息
            $productInfo = $this->productService->getProductInfo($productPurchaseFactor);
            $productPurchaseFactor->setProductInfo($productInfo);
            // 查询库存信息
            $cartStockInfo = $this->stockService->getStockInfo($productPurchaseFactor->product, $productPurchaseFactor->quantity);
            $productPurchaseFactor->setStockInfo($cartStockInfo);
            // 获取商品金额信息
            $productAmount = ShoppingOrderProductAmountHook::hook($productPurchaseFactor,
                fn() => $this->productService->getProductAmount($productPurchaseFactor)
            );
            $productPurchaseFactor->setProductAmount($productAmount);
            // 查询商品优惠信息
            $productAmount = $this->promotionService->getProductPromotion($productPurchaseFactor, $productAmount);

            // 获取商品拆单信息
            $productPurchaseFactor->setSplitKey(
                ShoppingOrderSplitProductHook::hook(
                    $productPurchaseFactor,
                    fn() => $this->orderService->getOrderProductSplitKey($productPurchaseFactor)
                )
            );

            // 把商品金额信息加入订单金额信息
            $productInfo->productAmount                                        = $productAmount;
            $productInfo->stockInfo                                            = $cartStockInfo;
            $orderAmount->products[$productPurchaseFactor->getKey() ?? $index] = $productInfo;
        }
        // 通过下单因子 TODO
        // 查询邮费信息 TODO
        // 查询订单优惠信息 TODO

        $orderAmount->calculate();

        return $orderAmount;
    }


}