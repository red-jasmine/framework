<?php

namespace RedJasmine\ShoppingCart\Domain\Services;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactors;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactors;
use RedJasmine\ShoppingCart\Domain\Contracts\ProductServiceInterface;
use RedJasmine\ShoppingCart\Domain\Contracts\StockServiceInterface;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCart;
use RedJasmine\ShoppingCart\Domain\Models\ShoppingCartProduct;
use RedJasmine\Support\Foundation\Service\Service;

class ShoppingCartDomainService extends Service
{

    public function __construct(
        protected ProductServiceInterface $productService,
        protected StockServiceInterface $stockService,
    ) {
    }

    public function addProduct(ShoppingCart $cart, ProductPurchaseFactors $productPurchaseFactors)
    {

    }


    public function calculates(ShoppingCart $cart, PurchaseFactors $factors)
    {


        $selectProducts = $cart->products->where('selected', true)->all();
        // TODO 验证货币是否一致， 需要一致时才支持选择
        $total = null;
        /**
         * @var ShoppingCartProduct $product
         */
        foreach ($selectProducts as $product) {
            $productPurchaseFactors = ProductPurchaseFactors::from([
                'product'    => $product->getProduct(),
                'quantity'   => $product->quantity,
                'customized' => $product->customized,
                ...$factors->toArray(),
            ]);

            // 获取商品信息
            $productInfo = $this->productService->getProductInfo($productPurchaseFactors);
            // TODO 验证商品是否可选
            // 获取价格
            $product->price = $this->productService->getProductPrice($productPurchaseFactors);

            // 获取库存
            //$cartStockInfo = $this->stockService->getAvailableStock($productPurchaseFactors->product, $productPurchaseFactors->quantity);
            // 获取优惠 TODO 验证是否允许下单 , 获取提示库存不足


            $subtotal = $product->price->multiply($product->quantity);
            if ($total === null) {
                $total = $subtotal;
            } else {
                $total = $total->add($subtotal);
            }
        }

        // 获取购物车级别优惠

        return $total;
    }

}