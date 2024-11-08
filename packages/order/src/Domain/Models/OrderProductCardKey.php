<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyContentTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\CardKeys\OrderCardKeyStatusEnum;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class OrderProductCardKey extends Model
{

    use HasSnowflakeId;

    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasTradeParties;

    use HasOperator;

    use SoftDeletes;

    public static function newModel() : static
    {
        $model     = new static();
        $model->id = $model->newUniqueId();

        return $model;
    }

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_product_card_keys';
    }

    protected $casts = [
        'entity_type'  => EntityTypeEnum::class,
        'content_type' => OrderCardKeyContentTypeEnum::class,
        'status'       => OrderCardKeyStatusEnum::class,
    ];

    public function orderProduct() : BelongsTo
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'id');
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
