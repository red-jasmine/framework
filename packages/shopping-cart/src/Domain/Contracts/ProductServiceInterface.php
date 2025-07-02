<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use Cknow\Money\Money;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactors;
use RedJasmine\ShoppingCart\Domain\Data\ProductInfo;

interface ProductServiceInterface
{
    /**
     * 获取商品信息
     *
     * @param  ProductPurchaseFactors  $productPurchaseFactors
     *
     * @return ProductInfo
     */
    public function getProductInfo(ProductPurchaseFactors $productPurchaseFactors) : ProductInfo;

    /**
     * 获取商品价格信息
     *
     * @param  ProductPurchaseFactors  $productPurchaseFactors
     *
     * @return Money|null
     */
    public function getProductPrice(ProductPurchaseFactors $productPurchaseFactors) : ?Money;




}