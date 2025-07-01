<?php

namespace RedJasmine\ShoppingCart\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProduct;
use RedJasmine\Support\Data\Data;

class ProductInfo extends Data
{
    // 商品身份信息
    // 商品基本信息
    // 商品价格信息
    // 商品规格属性
    // 商品图片
    public CartProduct $product;
    public string      $title;
    public string      $propertiesName;
    public ?string     $image = null;

    // 价格信息

    /**
     * 单价
     * @var Money
     */
    public Money $price;

    /**
     * 原价
     * @var ?Money
     */
    public ?Money $marketPrice;

    /**
     * 是否可用
     * @var bool
     */
    public bool $isAvailable;
}
