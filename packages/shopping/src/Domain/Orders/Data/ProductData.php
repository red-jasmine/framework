<?php

namespace RedJasmine\Shopping\Domain\Orders\Data;

use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Product\Domain\Stock\Models\ProductSku;
use RedJasmine\Support\Data\Data;

class ProductData extends Data
{

    public int $productId;

    public int $skuId;
    /**
     * 购物车ID
     * @var int|null
     */
    public ?int $ShoppingCartId;
    /**
     * 商品件数
     * @var int
     */
    public int $quantity;

    /**
     * 外部单号
     * @var string|null
     */
    public ?string $outerOrderProductId = null;

    /**
     *
     * @var string|null
     */
    public ?string $buyerRemarks;
    public ?string $buyerMessage;
    public ?array  $buyerExtras;
    public ?array  $tools;


    /**
     * 产品模型
     * @var Product
     */
    protected Product $product;
    protected ProductSku $sku;

    /**
     * 产品模型
     * @return Product
     */
    public function getProduct() : Product
    {
        return $this->product;
    }

    /**
     * @param  Product  $product
     */
    public function setProduct(Product $product) : void
    {
        $this->product = $product;
    }

    /**
     * @return ProductSku
     */
    public function getSku() : ProductSku
    {
        return $this->sku;
    }

    /**
     * @param  mixed  $sku
     */
    public function setSku(ProductSku $sku) : void
    {
        $this->sku = $sku;
    }

    protected string $splitKey;

    public function getSplitKey() : string
    {
        return $this->splitKey;
    }

    public function setSplitKey(string $splitKey) : ProductData
    {
        $this->splitKey = $splitKey;
        return $this;
    }







}
