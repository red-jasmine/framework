<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use RedJasmine\ShoppingCart\Domain\Data\ProductInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo;

interface ProductServiceInterface
{
    /**
     * 获取商品信息
     *
     * @param CartProduct  $product
     *
     * @return array|null
     */
    public function getProductInfo(CartProduct $product): ?ProductInfo;

    /**
     * 获取商品价格信息
     *
     * @param  CartProduct $product
     *
     * @return PriceInfo|null
     */
    public function getProductPrice(CartProduct $product): ?PriceInfo;

    /**
     * 校验商品是否可购买
     *
     * @param  CartProduct $product
     *
     * @return bool
     */
    public function isProductAvailable(CartProduct $product): bool;

    /**
     * 获取商品规格属性
     *
     * @param CartProduct $product
     *
     * @return array
     */
    public function getSkuProperties(CartProduct $product): array;
} 