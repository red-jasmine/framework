<?php

namespace RedJasmine\Product\Domain\Stock\Data;

use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class StockActionData extends StockCoreData
{
    /**
     * 操作类型
     *
     *
     * @var ProductStockActionTypeEnum
     */
    #[WithCast(EnumCast::class, ProductStockActionTypeEnum::class)]
    public ProductStockActionTypeEnum $actionType;

    /**
     * 操作库存数量
     * 根据动作类型执行相应的库存操作数量
     *
     * @var int
     */
    public int $actionStock;

    /**
     * 业务类型
     * @var ?string
     */
    public ?string $businessType = null;

    /**
     * 业务单号
     * @var string|null
     */
    public ?string $businessNo = null;


}
