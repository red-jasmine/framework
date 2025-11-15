<?php

namespace RedJasmine\Product\Domain\Price\Data;

use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Data\Data;

/**
 * 商品价格命令数据
 *
 * 变体价格数据
 */
class ProductPriceVariantData extends Data
{
    public int $variantId;
    public Money $price;
    public ?Money $marketPrice = null;
    public ?Money $costPrice = null;
}

/**
 * 商品价格批量命令数据
 */
class ProductPriceCommandData extends Data
{
    public int $productId;
    /** @var ProductPriceVariantData[] */
    public array $variants;
    public string $market = '*';
    public string $store = '*';
    public string $userLevel = '*';
    public string $currency;
    public ?array $quantityTiers = null;
    public int $priority = 0;
}

