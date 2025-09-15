<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Cknow\Money\Money;
use Money\Currency;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Casts\CurrencyCast;
use Spatie\LaravelData\Attributes\WithCast;


class Sku extends Data
{
    public string  $propertiesSequence;
    public ?string $propertiesName;

    public ProductStatusEnum $status = ProductStatusEnum::ON_SALE;

    public ?string $image         = null;
    public ?string $barcode       = null;
    public ?string $outerId       = null;
    public ?int    $supplierSkuId = null;


    #[WithCast(CurrencyCast::class)]
    public Currency $currency;

    public Money $price;
    /**
     * 市场价格
     *
     * @var Money|null
     */
    public ?Money $marketPrice ;
    /**
     * 成本价格
     *
     * @var Money|null
     */
    public ?Money $costPrice = null;


    public int $stock       = 0;
    public int $safetyStock = 0;
    // 重量（可选）
    public ?string $weight;
    // 宽度（可选）
    public ?string $width;
    // 高度（可选）
    public ?string $height;
    // 长度（可选）
    public ?string $length;
    // 尺寸（可选）
    public ?string $size;
}
