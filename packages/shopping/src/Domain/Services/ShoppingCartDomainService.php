<?php

namespace RedJasmine\Shopping\Domain\Services;

use Money\Currency;
use PHPUnit\Event\InvalidArgumentException;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Shopping\Application\Services\ShoppingCart\Commands\AddProductCommand;
use RedJasmine\Shopping\Domain\Contracts\ProductServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\PromotionServiceInterface;
use RedJasmine\Shopping\Domain\Contracts\StockServiceInterface;
use RedJasmine\Shopping\Domain\Data\OrderAmountData;
use RedJasmine\Shopping\Domain\Data\ProductInfo;
use RedJasmine\Shopping\Domain\Data\StockInfo;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Models\ShoppingCartProduct;
use RedJasmine\Support\Foundation\Service\Service;

class ShoppingCartDomainService extends AmountCalculationService
{


    /**
     * 校验商品信息
     */
    protected function validateProduct(ProductInfo $productInfo) : bool
    {

        if (!$productInfo->isAvailable) {
            throw new InvalidArgumentException('商品不可购买');
        }

        return true;
    }

    /**
     * 校验库存
     */
    protected function validateStock(StockInfo $stockInfo) : bool
    {
        if (!$stockInfo->isAvailable) {
            throw new InvalidArgumentException("库存不足，可用库存：{$stockInfo->stock}");
        }
        return true;
    }


    public function addProduct(ShoppingCart $cart, ProductPurchaseFactor $productPurchaseFactors) : ShoppingCartProduct
    {

        $shoppingCartProduct = ShoppingCartProduct::make(['cart_id' => $cart->id]);
        $shoppingCartProduct->setProduct($productPurchaseFactors->product);
        $shoppingCartProduct->quantity   = $productPurchaseFactors->quantity;
        $shoppingCartProduct->customized = $productPurchaseFactors->customized;

        $shoppingCartProduct = $cart->addProduct($shoppingCartProduct);

        $productPurchaseFactors->quantity = $shoppingCartProduct->quantity;

        // 获取商品信息
        $productInfo = $this->productService->getProductInfo($productPurchaseFactors);
        $this->validateProduct($productInfo);

        // 获取库存信息
        // 6. 校验库存
        $stockInfo = $this->stockService->getStockInfo($productPurchaseFactors->product, $shoppingCartProduct->quantity);
        $this->validateStock($stockInfo);

        // 7. 获取价格 已最终的数量 获取价格
        $priceInfo = $this->productService->getProductAmount($productPurchaseFactors);

        $shoppingCartProduct->setProductInfo($productInfo);
        $shoppingCartProduct->price = $priceInfo->price;

        return $shoppingCartProduct;
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

            $productPurchaseFactor = ProductPurchaseFactor::from([
                'product'    => $product->getProduct(),
                'quantity'   => $product->quantity,
                'customized' => $product->customized,
                'buyer'      => $factor->buyer,
                'channel'    => $factor->channel,
                'currency'   => $factor->currency,
                'country'    => $factor->country,
                'market'     => $factor->market,
            ]);
            $productPurchaseFactor->setKey($product->id);

            $productPurchaseFactors[] = $productPurchaseFactor;
        }

        return $this->getOrderAmount($productPurchaseFactors);
    }


}