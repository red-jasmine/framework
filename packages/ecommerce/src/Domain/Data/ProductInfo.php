<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Support\Data\Data;

class ProductInfo extends Data
{

    /**
     * 是否允许购买 = 失效
     * @var bool
     */
    public bool $isAvailable;
    // 商品身份信息
    // 商品基本信息
    // 商品价格信息
    // 商品规格属性
    // 商品图片
    public ProductIdentity $product;


    public ProductTypeEnum $productType;
    /**
     * 允许的发货方式
     * @var ShippingTypeEnum[]
     */
    public array   $shippingTypes;
    public string  $title;
    public string  $propertiesName;
    public ?string $image    = null;
    public int     $minLimit = 1;
    // 数量步幅
    public int $stepLimit = 1;
    // 0 表示不限制
    public int $maxLimit = 0;

    /**
     * 商品类目ID
     * @var int
     */
    public int $categoryId     = 0;
    public int $brandId        = 0;
    public int $productGroupId = 0;
    public int $barcode        = 0;

    // 价格信息
    public ProductAmount $productAmount;

    /**
     * 库存信息
     * @var StockInfo
     */
    public StockInfo $stockInfo;

    // 优惠信息


}
