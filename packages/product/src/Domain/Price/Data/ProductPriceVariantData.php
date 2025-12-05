<?php

namespace RedJasmine\Product\Domain\Price\Data;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;

/**
 * 商品价格命令数据
 *
 * 变体价格数据
 */
class ProductPriceVariantData extends Data
{
    public int    $variantId;
    public Money  $price;
    public ?Money $marketPrice = null;
    public ?Money $costPrice   = null;
}