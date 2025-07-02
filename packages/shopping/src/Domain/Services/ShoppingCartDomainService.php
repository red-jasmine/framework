<?php

namespace RedJasmine\Shopping\Domain\Services;

use Money\Currency;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderAmountData;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Models\ShoppingCartProduct;
use RedJasmine\Support\Foundation\Service\Service;

class ShoppingCartDomainService extends Service
{

    public function __construct(
        protected ProductServiceInterface $productService,
        protected StockServiceInterface $stockService,
        protected PromotionServiceInterface $promotionService
    ) {
    }

    public function addProduct(ShoppingCart $cart, ProductPurchaseFactor $productPurchaseFactors)
    {

    }


    public function calculates(ShoppingCart $cart, PurchaseFactor $factor) : OrderAmountData
    {

        $selectProducts         = $cart->products->where('selected', true)->all();
        $productPurchaseFactors = [];
        // TODO 验证货币是否一致， 需要一致时才支持选择
        /**
         * @var ShoppingCartProduct $product
         */
        foreach ($selectProducts as $product) {

            $productPurchaseFactors[] = ProductPurchaseFactor::from([
                'product'    => $product->getProduct(),
                'quantity'   => $product->quantity,
                'customized' => $product->customized,
                'buyer'      => $factor->buyer,
                'channel'    => $factor->channel,
                'currency'   => $factor->currency,
                'country'    => $factor->country,
                'market'     => $factor->market,
            ]);

        }


        return $this->getOrderAmount($productPurchaseFactors);
    }


    /**
     * @param  array|ProductPurchaseFactor[]  $productPurchaseFactors
     *
     * @return OrderAmountData
     */
    protected function getOrderAmount(array $productPurchaseFactors) : OrderAmountData
    {
        foreach ($productPurchaseFactors as $productPurchaseFactor) {
            $productAmount = $this->productService->getProductAmount($productPurchaseFactor);
            // 获取商品信息
            $productInfo = $this->productService->getProductInfo($productPurchaseFactor);
            // TODO 验证商品是否可选
            // 根据 价格体系 获取  单价 和 总价

            $orderAmount = $orderAmount ?? new OrderAmountData($productAmount->price->getCurrency());

            // 获取库存  TODO 验证是否允许下单 , 获取提示库存不足
            $cartStockInfo = $this->stockService->getAvailableStock($productPurchaseFactor->product, $productPurchaseFactor->quantity);

            // 获取优惠
            $productAmount = $this->promotionService->getProductPromotion($productPurchaseFactor, $productAmount);


            $orderAmount->productAmounts[] = $productAmount;
        }
        $orderAmount = $orderAmount ?? new OrderAmountData(new Currency('CNY'));

        // TODO
        // 计算订单级别 运费
        // TODO 预计、已最终结算为准
        // 获取订单级别优惠

        $orderAmount->calculate();


        return $orderAmount;
    }


}