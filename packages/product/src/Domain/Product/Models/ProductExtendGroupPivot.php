<?php

namespace RedJasmine\Product\Domain\Product\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;
use RedJasmine\Product\Domain\Group\Models\ProductGroup;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class ProductExtendGroupPivot extends Pivot
{

    public $incrementing = true;

    use HasDateTimeFormatter;



    public function productGroup() : BelongsTo
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id', 'id');
    }
}
