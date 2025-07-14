<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\Product\ProductAmountInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\Product\ProductPurchaseFactor;

interface ProductServiceInterface
{
    /**
     * 获取商品信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return ProductInfo
     */
    public function getProductInfo(ProductPurchaseFactor $productPurchaseFactor) : ProductInfo;

    /**
     * 获取商品价格信息
     *
     * @param  ProductPurchaseFactor  $productPurchaseFactor
     *
     * @return ProductAmountInfo
     */
    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmountInfo;


}