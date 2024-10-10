<?php

namespace RedJasmine\Product\Domain\Product\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Category\Models\ProductSellerCategory;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class ProductSellerExtendCategoryPivot extends Pivot
{

    public $incrementing = true;

    use HasDateTimeFormatter;

    /**
     * @return string
     */
    public function getTable()
    {
        return config('red-jasmine-product.tables.prefix') . Str::snake(Str::pluralStudly(class_basename($this)));
    }


    public function sellerCategory() : BelongsTo
    {
        return $this->belongsTo(ProductSellerCategory::class, 'seller_category_id', 'id');
    }
}
