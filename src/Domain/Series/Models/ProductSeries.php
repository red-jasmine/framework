<?php

namespace RedJasmine\Product\Domain\Series\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class ProductSeries extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use HasOwner;

    use HasOperator;

    public $incrementing = false;

    public function getTable()
    {
        return config('red-jasmine-product.tables.prefix') . Str::snake(Str::pluralStudly(class_basename($this)));;
    }

    public function products() : HasMany
    {
        return $this->hasMany(ProductSeriesProduct::class, 'series_id', 'id');
    }

}
