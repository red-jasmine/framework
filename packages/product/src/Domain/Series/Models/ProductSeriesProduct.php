<?php

namespace RedJasmine\Product\Domain\Series\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Product\Models\Product;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class ProductSeriesProduct extends Model
{
    use HasDateTimeFormatter;

    protected $fillable = [
        'series_id', 'product_id', 'position'
    ];


    public function series() : BelongsTo
    {
        return $this->belongsTo(ProductSeries::class, 'series_id', 'id');
    }


    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
