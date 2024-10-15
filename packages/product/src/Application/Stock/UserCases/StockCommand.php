<?php

namespace RedJasmine\Product\Application\Stock\UserCases;


use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class StockCommand extends Data
{

    public int                        $skuId;
    public int                        $productId;
    public ProductStockActionTypeEnum $actionType;
    public int                        $actionStock;
    public ProductStockChangeTypeEnum $changeType = ProductStockChangeTypeEnum::SELLER;

    public ?string $changeDetail = null;
    public ?string $channelType  = null;
    public ?int    $channelId    = null;


    public static function attributes(...$args) : array
    {
        return [
            'stock' => '库存数量',

        ];
    }

    public static function rules(ValidationContext $context) : array
    {
        return [
            'stock' => [ 'min:0' ]
        ];
    }

}
