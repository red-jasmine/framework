<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use RedJasmine\Ecommerce\Domain\Helpers\HasSerialNumber;

/**
 * 商品价格因子
 */
class ProductPurchaseFactor extends PurchaseFactor
{
    use HasSerialNumber;

    /**
     * 商品
     * @var ProductIdentity
     */
    public ProductIdentity $product;
    /**
     * 定制信息
     * @var array|null
     */
    public ?array $customized = [];

    /**
     * 数量
     * @var int
     */
    public int $quantity = 1;


    protected ProductInfo $productInfo;


    public function getProductInfo() : ProductInfo
    {
        return $this->productInfo;
    }

    public function setProductInfo(ProductInfo $productInfo) : void
    {
        $this->product     = $productInfo->product;
        $this->productInfo = $productInfo;
    }


}