<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;
use RedJasmine\Shopping\Domain\Data\ProductAmountData;
use RedJasmine\Shopping\Domain\Data\ProductInfo;

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
     * @return ProductAmountData
     */
    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmountData;


}