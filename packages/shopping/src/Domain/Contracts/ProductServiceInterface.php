<?php

namespace RedJasmine\Shopping\Domain\Contracts;

use RedJasmine\Ecommerce\Domain\Data\ProductAmount;
use RedJasmine\Ecommerce\Domain\Data\ProductInfo;
use RedJasmine\Ecommerce\Domain\Data\ProductPurchaseFactor;

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
     * @return ProductAmount
     */
    public function getProductAmount(ProductPurchaseFactor $productPurchaseFactor) : ProductAmount;


}