<?php

namespace RedJasmine\ShoppingCart\Domain\Contracts;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\PriceInfo;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;

interface ProductServiceInterface
{
    /**
     * 获取商品信息
     *
     * @param CartProductIdentity $identity
     * @return array|null
     */
    public function getProductInfo(CartProductIdentity $identity): ?array;

    /**
     * 获取商品价格信息
     *
     * @param CartProductIdentity $identity
     * @return PriceInfo|null
     */
    public function getProductPrice(CartProductIdentity $identity): ?PriceInfo;

    /**
     * 校验商品是否可购买
     *
     * @param CartProductIdentity $identity
     * @return bool
     */
    public function isProductAvailable(CartProductIdentity $identity): bool;

    /**
     * 获取商品规格属性
     *
     * @param CartProductIdentity $identity
     * @return array
     */
    public function getSkuProperties(CartProductIdentity $identity): array;
} 