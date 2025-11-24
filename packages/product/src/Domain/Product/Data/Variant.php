<?php

namespace RedJasmine\Product\Domain\Product\Data;

use RedJasmine\Money\Data\Money;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Product\Domain\Stock\Data\WarehouseStockData;
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


    public ?string $image = null;
    /**
     * sku
     * @var string|null
     */
    public ?string $sku = null;
    /**
     * 条码
     * @var string|null
     */
    public ?string $barcode = null;


    /**
     * @var WarehouseStockData[]
     */
    public array $stocks = [];

    // 包装单位（可选）
    public ?string $packageUnit = null;
    // 包装数量
    public int $packageQuantity = 1;

    // 重量（可选）
    public ?string $weight;

    // 重量单位
    public ?string $weightUnit = 'kg';

    // 尺寸单位
    public ?string $dimensionUnit = 'm';
    // 宽度（可选）
    public ?string $width;
    // 高度
    public ?string $height;
    // 长度（可选）
    public ?string $length;

    // 体积（可选）
    public ?string $volume;

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
