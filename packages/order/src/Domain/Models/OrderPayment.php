<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class OrderPayment extends Model
{

    use HasSnowflakeId;

    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasOperator;

    use HasTradeParties;

    protected $casts = [
        'entity_type' => EntityTypeEnum::class,
        'amount_type' => AmountTypeEnum::class,
        'status'      => PaymentStatusEnum::class,
    ];

    public static function newModel() : static
    {
        $model     = new static();
        $model->id = $model->newUniqueId();

        return $model;
    }

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_payments';
    }


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function entity() : MorphTo
    {
        return $this->morphTo();
    }
}
