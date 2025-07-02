<?php

namespace RedJasmine\Shopping\Domain\Data;

use RedJasmine\Ecommerce\Domain\Data\ProductIdentity;
use RedJasmine\Support\Data\Data;

class ProductInfo extends Data
{
    // 商品身份信息
    // 商品基本信息
    // 商品价格信息
    // 商品规格属性
    // 商品图片
    public ProductIdentity $product;
    public string          $title;
    public string          $propertiesName;
    public ?string         $image = null;


    // 价格信息


    /**
     * 是否可用
     * @var bool
     */
    public bool $isAvailable;
}
