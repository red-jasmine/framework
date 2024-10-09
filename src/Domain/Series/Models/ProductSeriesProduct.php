<?php

namespace RedJasmine\Product\Domain\Series\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class ProductSeriesProduct extends Model
{
    use HasDateTimeFormatter;

    protected $fillable = [
        'series_id', 'product_id', 'name'
    ];
    /**
     * @return string
     */
    public function getTable()
    {
        return config('red-jasmine-product.tables.prefix') .Str::snake(Str::pluralStudly(class_basename($this)));;
    }
    public function series() : BelongsTo
    {
        return $this->belongsTo(ProductSeries::class, 'series_id', 'id');
    }
}
