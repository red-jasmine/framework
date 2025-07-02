<?php

namespace RedJasmine\Ecommerce\Domain\Data;

use RedJasmine\Ecommerce\Domain\Models\ValueObjects\ProductIdentity;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\Data\Data;

/**
 * 商品价格因子
 */
class ProductPurchaseFactors extends PurchaseFactors
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