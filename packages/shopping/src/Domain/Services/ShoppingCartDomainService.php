<?php

namespace RedJasmine\Shopping\Domain\Services;

use PHPUnit\Event\InvalidArgumentException;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderData;
use RedJasmine\Ecommerce\Domain\Data\Order\OrderProductData;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;
use RedJasmine\Ecommerce\Domain\Data\Product\StockInfo;
use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
use RedJasmine\Shopping\Domain\Models\ShoppingCart;
use RedJasmine\Shopping\Domain\Models\ShoppingCartProduct;
use RedJasmine\Shopping\Exceptions\ShoppingCartException;

class ShoppingCartDomainService extends AmountCalculationService
{


    /**
     * @param  ShoppingCart  $cart
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return ShoppingCartProduct
     * @throws ShoppingCartException
     */
    public function addProduct(ShoppingCart $cart, ProductPurchaseFactor $productPurchaseFactor) : ShoppingCartProduct
    {

        // 获取商品信息
        $productInfo = $this->productService->getProductInfo($productPurchaseFactor);
        $productPurchaseFactor->setProductInfo($productInfo);
        /**
         * @var ShoppingCartProduct $shoppingCartProduct
         */
        $shoppingCartProduct = ShoppingCartProduct::make(['cart_id' => $cart->id]);

        $shoppingCartProduct->setProduct($productPurchaseFactor->getProductInfo()->product);
        $shoppingCartProduct->quantity   = $productPurchaseFactor->quantity;
        $shoppingCartProduct->customized = $productPurchaseFactor->customized;

        $shoppingCartProduct = $cart->addProduct($shoppingCartProduct);

        $productPurchaseFactor->quantity = $shoppingCartProduct->quantity;

        $this->validateProduct($productInfo);

        // 获取库存信息
        // 6. 校验库存
        $stockInfo = $this->stockService->getStockInfo($productPurchaseFactor->product, $shoppingCartProduct->quantity);
        $this->validateStock($stockInfo);

        // 7. 获取价格 已最终的数量 获取价格
        $priceInfo = $this->productService->getProductAmount($productPurchaseFactor);

        $shoppingCartProduct->setProductInfo($productInfo);
        $shoppingCartProduct->price = $priceInfo->price;

        return $shoppingCartProduct;
    }

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


    /**
     * @param  ShoppingCartProduct[]  $selectProducts
     * @param  PurchaseFactor  $factor
     *
     * @return OrderData
     */
    protected function getSelectProductsOrderAmount(array $selectProducts, PurchaseFactor $factor) : OrderData
    {
        // TODO 验证货币是否一致， 需要一致时才支持选择

        $orderData = OrderData::from([
            'buyer'   => $factor->buyer,
            'channel' => $factor->channel,
            'market'  => $factor->market
        ]);
        foreach ($selectProducts as $product) {
            $productPurchaseFactor = OrderProductData::from([
                'product'          => $product->getProduct(),
                'quantity'         => $product->quantity,
                'customized'       => $product->customized,
                'buyer'            => $factor->buyer,
                'channel'          => $factor->channel,
                'currency'         => $factor->currency,
                'country'          => $factor->country,
                'market'           => $factor->market,
                'shopping_cart_id' => $product->id,
            ]);

            $orderData->products[] = $productPurchaseFactor;
        }


        return $this->calculateOrderAmount($orderData);
    }

    public function calculates(
        ShoppingCart $cart,
        PurchaseFactor $factor,
        $onlySelected = true
    ) : OrderData {

        if ($onlySelected) {
            $selectProducts = $cart->products->where('selected', true)->all();
        } else {
            $selectProducts = $cart->products->all();
        }


        return $this->getSelectProductsOrderAmount($selectProducts, $factor);
    }


}