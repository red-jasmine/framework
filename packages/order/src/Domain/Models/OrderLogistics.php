<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class OrderLogistics extends Model
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
        return config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_logistics';
    }

    protected $casts = [
        'order_product_id' => 'array',
        'shipper'          => LogisticsShipperEnum::class,
        'entity_type'      => EntityTypeEnum::class,
        'status'           => LogisticsStatusEnum::class,
        'expands'          => 'array',
        'shipping_time'    => 'datetime',
        'collect_time'     => 'datetime',
        'dispatch_time'    => 'datetime',
        'signed_time'      => 'datetime',
    ];

    public function entity() : MorphTo
    {
        return $this->morphTo();
    }
}
