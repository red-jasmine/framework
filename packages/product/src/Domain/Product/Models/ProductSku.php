<?php

namespace RedJasmine\Product\Domain\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Product\Domain\Product\Models\Enums\ProductStatusEnum;
use RedJasmine\Support\Domain\Casts\AmountCast;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class ProductSku extends Model implements OperatorInterface
{

    use HasSnowflakeId;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasOwner;

    public $incrementing = false;


    protected $casts = [
        'status'        => ProductStatusEnum::class,// 状态
        'modified_time' => 'datetime',
        'price'         => AmountCast::class,
        'market_price'  => AmountCast::class,
        'cost_price'    => AmountCast::class,
    ];

    protected $appends  = ['price','market_price','cost_price'];

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
