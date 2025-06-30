<?php

namespace RedJasmine\ShoppingCart\Domain\Data;

use RedJasmine\ShoppingCart\Domain\Models\ValueObjects\CartProductIdentity;
use RedJasmine\Support\Data\Data;

class ProductInfoData extends Data
{
    // 商品身份信息
    // 商品基本信息
    // 商品价格信息
    // 商品规格属性
    // 商品图片

    public CartProductIdentity $identity;
    public string              $title;
    public ?string             $image = null;
}
