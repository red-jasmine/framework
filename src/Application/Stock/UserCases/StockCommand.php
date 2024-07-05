<?php

namespace RedJasmine\Product\Application\Stock\UserCases;


use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Support\Data\Data;

class StockCommand extends Data
{

    public int                        $skuId;
    public int                        $productId;
    public int                        $stock;
    public ProductStockChangeTypeEnum $changeType   = ProductStockChangeTypeEnum::SELLER;
    public ?string                    $changeDetail = null;
    public ?string                    $channelType  = null;
    public ?int                       $channelId    = null;

}
