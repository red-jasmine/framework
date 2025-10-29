<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Cknow\Money\Money;
use Money\Currency;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Casts\CurrencyCast;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use Spatie\LaravelData\Attributes\WithCast;


class Variant extends Data
{
    // 属性序列 如 1000:100010;2000:200020;
    public string $propertiesSequence;
    // 属性序列名称 如 颜色:黑色,尺寸:L
    public ?string $propertiesName;

    public ProductStatusEnum $status = ProductStatusEnum::ON_SALE;
    public Money             $price;
    /**
     * 市场价格
     *
     * @var Money|null
     */
    public ?Money $marketPrice;
    /**
     * 成本价格
     *
     * @var Money|null
     */
    public ?Money $costPrice = null;


    public ?string $sku     = null;
    public ?string $image   = null;
    public ?string $barcode = null;


    public int $stock       = 0;
    public int $safetyStock = 0;


    // 重量（可选）
    public ?string $weight;
    // 重量单温
    public ?string $weightUnit = 'kg';
    // 宽度（可选）
    public ?string $width;
    // 高度（可选）
    public ?string $height;
    // 长度（可选）
    public ?string $length;

    // 体积（可选）
    public ?string $volume;
    // 尺寸单位
    public ?string $dimensionUnit = 'm';

}
