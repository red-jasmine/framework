<?php

namespace RedJasmine\Shopping\Application\UserCases\Commands\Data;

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
    public int $num;

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
    public ?array  $buyerExpands;
    public ?array  $tools;


    protected $product;

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product) : void
    {
        $this->product = $product;
    }


    protected $sku;

    /**
     * @return ProductSku
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku) : void
    {
        $this->sku = $sku;
    }


}
