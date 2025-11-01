<?php

namespace RedJasmine\Product\Domain\Product\Data;

use Cknow\Money\Money;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Data\Data;


class Variant extends Data
{
    // 属性序列 如 1000:100010;2000:200020;
    /**
     * @var ?string
     */
    public ?string $attrsSequence = null;
    // 属性序列名称 如 颜色:黑色,尺寸:L
    public ProductStatusEnum $status = ProductStatusEnum::AVAILABLE;
    /**
     * @var Money
     */
    public Money $price;
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
    /**
     * sku
     * @var string|null
     */
    public ?string $sku         = null;
    public ?string $image       = null;
    public ?string $barcode     = null;
    public int     $stock       = 0;
    public int     $safetyStock = 0;
    public ?string $weight;


    // 重量（可选）
    public ?string $weightUnit = 'kg';
    // 重量单温
    public ?string $width;
    // 宽度（可选）
    public ?string $height;
    // 高度（可选）
    public ?string $length;
    // 长度（可选）
    public ?string $volume;

    // 体积（可选）
    public ?string $dimensionUnit = 'm';
    // 尺寸单位
    /**
     * @var string|null
     */
    protected ?string $attrsName = null;

    public function getAttrsName() : ?string
    {
        return $this->attrsName;
    }

    public function setAttrsName(?string $attrsName) : void
    {
        $this->attrsName = $attrsName;
    }




}
