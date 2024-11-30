<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockChangeTypeEnum;
use RedJasmine\Product\Domain\Stock\Models\Enums\ProductStockActionTypeEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class ProductStockLog extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    /**
     * @return string
     */
    public function getTable() : string
    {
        return config('red-jasmine-product.tables.prefix','jasmine_') . Str::snake(Str::pluralStudly(class_basename($this)));;
    }


    protected $casts = [
        'action_type' => ProductStockActionTypeEnum::class,
        'change_type' => ProductStockChangeTypeEnum::class,
    ];


    public function sku() : BelongsTo
    {
        return $this->belongsTo(ProductSku::class, 'sku_id', 'id');
    }


    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
