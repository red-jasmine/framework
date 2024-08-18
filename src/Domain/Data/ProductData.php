<?php

namespace RedJasmine\Shopping\Domain\Data;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use RedJasmine\Support\Data\Data;

class ProductData extends Data
{


    /**
     * 商品ID
     * @var int
     */
    public int $productId;
    /**
     * 规格ID
     * @var int
     */
    public int $skuId;
    /**
     * 商品件数
     * @var int
     */
    public int $num;


    public Amount $price;
    /**
     * 产品金额
     * @var Amount
     */
    public Amount $productAmount;
    /**
     * 税
     * @var Amount
     */
    public Amount $taxAmount;
    /**
     * 优惠金额
     * @var Amount
     */
    public Amount $discountAmount;
    /**
     * 商品应付金额
     * @var Amount
     */
    public Amount $payableAmount;
}
