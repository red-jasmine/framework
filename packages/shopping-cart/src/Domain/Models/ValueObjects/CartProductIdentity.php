<?php

namespace RedJasmine\ShoppingCart\Domain\Models\ValueObjects;

use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

/**
 * 购物车商品唯一标识值对象
 */
class CartProductIdentity extends ValueObject
{

    public string $shopType;
    public string $shopId;
    public string $productType;
    public string $productId;
    public string $skuId;

    public function equals(self $other) : bool
    {
        return $this->shopType === $other->shopType
               && $this->shopId === $other->shopId
               && $this->productType === $other->productType
               && $this->productId === $other->productId
               && $this->skuId === $other->skuId;
    }

    public function __toString() : string
    {
        return implode(':', [$this->shopType, $this->shopId, $this->productType, $this->productId, $this->skuId]);
    }
}
