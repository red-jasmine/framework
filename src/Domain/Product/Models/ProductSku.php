<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Ecommerce\Domain\Models\Casts\AmountCastTransformer;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;


class ProductSku extends Model implements OperatorInterface
{

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasOwner;

    public $incrementing = false;


    protected $casts = [
        'status'        => ProductStatusEnum::class,// 状态
        'modified_time' => 'datetime',
        'price'         => AmountCastTransformer::class,
        'market_price'  => AmountCastTransformer::class,
        'cost_price'    => AmountCastTransformer::class,
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


    public function setDeleted() : void
    {
        $this->deleted_at = $this->deleted_at ?? now();
        $this->status     = ProductStatusEnum::DELETED;
    }

    public function setOnSale() : void
    {
        $this->deleted_at = null;
        $this->status     = ProductStatusEnum::ON_SALE;
    }

}
