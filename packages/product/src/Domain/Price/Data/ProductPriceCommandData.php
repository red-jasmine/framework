<?php

namespace RedJasmine\Product\Domain\Price\Data;

use Money\Currency;
use RedJasmine\Money\Casts\CurrencyCast;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;


/**
 * 商品价格批量命令数据
 */
class ProductPriceCommandData extends Data
{
    public int $productId;
    /** @var ProductPriceVariantData[] */
    public array  $variants;
    public string $market    = '*';
    public string $store     = '*';
    public string $userLevel = '*';
    /**
     * 商品价格货币
     * 货币与市场相关、需要验证 TODO
     * @var Currency
     */
    #[WithCast(CurrencyCast::class)]
    public Currency $currency;
    /**
     * 数量阶梯、默认为 1
     * @var int
     */
    public int $quantity = 1;

}

