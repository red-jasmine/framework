<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockTypeEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;


class ProductStockLog extends Model implements OperatorInterface
{

    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    protected $casts = [
        'type'        => ProductStockTypeEnum::class,
        'change_type' => ProductStockChangeTypeEnum::class,
        'expands'     => 'array'
    ];


    public function sku() : BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'sku_id', 'id');
    }

}
