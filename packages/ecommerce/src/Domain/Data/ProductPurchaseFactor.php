<?php

namespace RedJasmine\Ecommerce\Domain\Data;

/**
 * 商品价格因子
 */
class ProductPurchaseFactor extends PurchaseFactor
{


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





}