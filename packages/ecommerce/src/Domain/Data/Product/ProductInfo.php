<?php

namespace RedJasmine\Ecommerce\Domain\Data\Product;

use RedJasmine\Ecommerce\Domain\Models\Enums\ProductTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\ShippingTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;

class ProductInfo extends Data
{

    /**
     * 商品身份信息
     * @var ProductIdentity
     */
    public ProductIdentity $product;


    // 通过商品身份信息 获取的 基础信息


    /**
     * 是否允许购买 = 失效
     * @var bool
     */
    public bool $isAvailable;


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
    public int     $categoryId     = 0;
    public int     $brandId        = 0;
    public int     $productGroupId = 0;
    public ?string $barcode        = null;

    public ?string $spu = null;
    public ?string $sku = null;


    // 价格信息


    public ProductAmountInfo $productAmountInfo;


    /**
     * 库存信息
     * @var StockInfo
     */
    public StockInfo $stockInfo;
    /**
     * 拆分key
     * @var string
     */
    protected string $splitKey;

    public function getStockInfo() : StockInfo
    {
        return $this->stockInfo;
    }

    public function setStockInfo(StockInfo $stockInfo) : void
    {
        $this->stockInfo = $stockInfo;
    }

    public function getProductAmountInfo() : ProductAmountInfo
    {
        return $this->productAmountInfo;
    }

    public function setProductAmountInfo(ProductAmountInfo $productAmount) : void
    {
        $this->productAmountInfo = $productAmount;
    }

    public function getSplitKey() : string
    {
        return $this->splitKey;
    }

    public function setSplitKey(string $splitKey) : static
    {
        $this->splitKey = $splitKey;
        return $this;
    }

    // 优惠信息


}
