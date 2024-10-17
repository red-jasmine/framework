<?php

namespace RedJasmine\Product\Domain\Product\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use RedJasmine\Product\Domain\Tag\Models\ProductTag;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class ProductServicePivot extends Pivot
{

    public $incrementing = true;

    use HasDateTimeFormatter;

    /**
     * @return string
     */
    public function getTable() : string
    {
        return config('red-jasmine-product.tables.prefix') . 'product_service_pivots';
    }


    public function productGroup() : BelongsTo
    {
        return $this->belongsTo(ProductTag::class, 'product_service_id', 'id');
    }
}
