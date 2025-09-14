<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Cknow\Money\Money;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;


class Sku extends Data
{
    public string  $propertiesSequence;
    public ?string $propertiesName;

    public ProductStatusEnum $status = ProductStatusEnum::ON_SALE;

    public ?string $image         = null;
    public ?string $barcode       = null;
    public ?string $outerId       = null;
    public ?int    $supplierSkuId = null;

    /**
     * 币种
     * @var string
     */
    public string $currency = 'CNY';

    public string $price;
    /**
     * 市场价格
     *
     * @var string|null
     */
    public ?string $marketPrice = null;
    /**
     * 成本价格
     *
     * @var string|null
     */
    public ?string $costPrice = null;


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
