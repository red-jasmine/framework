<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsShipperEnum;
use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;

class OrderLogistics extends Model
{

    public $incrementing = false;


    use HasDateTimeFormatter;

    use HasTradeParties;

    use HasOperator;

    use SoftDeletes;


    protected $casts = [
        'order_product_id' => 'array',
        'shipper'          => LogisticsShipperEnum::class,
        'status'           => LogisticsStatusEnum::class,
        'expands'          => 'array',
        'shipping_time'    => 'datetime',
        'collect_time'     => 'datetime',
        'dispatch_time'    => 'datetime',
        'signed_time'      => 'datetime',
    ];

    public function shippable() : MorphTo
    {
        return $this->morphTo();
    }
}
