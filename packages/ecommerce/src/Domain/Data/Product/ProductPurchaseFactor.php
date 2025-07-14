<?php

namespace RedJasmine\Ecommerce\Domain\Data\Product;

use RedJasmine\Ecommerce\Domain\Data\PurchaseFactor;
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


    /**
     * 购物车ID
     * @var string|null
     */
    public ?string $shoppingCartId = null;

    // 通过商品身份信息获取的 商品信息
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