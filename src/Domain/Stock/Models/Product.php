<?php

namespace RedJasmine\Product\Domain\Stock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;


class Product extends Model implements OperatorInterface
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOwner;

    use HasOperator;

    public $incrementing = false;


    public function skus() : HasMany
    {
        return $this->hasMany(ProductSku::class, 'product_id', 'id');
    }

}
