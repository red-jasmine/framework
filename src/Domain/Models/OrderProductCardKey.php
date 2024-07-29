<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Models\Enums\OrderCardKeyStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderProductCardKey extends Model
{


    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasTradeParties;

    use \RedJasmine\Support\Domain\Models\Traits\HasOperator;

    use SoftDeletes;

    protected $casts = [
        'extends' => 'array',
        'status'  => OrderCardKeyStatusEnum::class,
    ];

    public function orderProduct() : BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'id');
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
