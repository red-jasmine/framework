<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Events\OrderLogisticsChangedStatusEvent;
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


    protected $casts = [
        'order_product_no' => 'array',
        'shipper'          => LogisticsShipperEnum::class,
        'entity_type'      => EntityTypeEnum::class,
        'status'           => LogisticsStatusEnum::class,
        'extra'            => 'array',
        'shipping_time'    => 'datetime',
        'collect_time'     => 'datetime',
        'dispatch_time'    => 'datetime',
        'signed_time'      => 'datetime',
    ];


    protected $dispatchesEvents = [
        'changedStatus' => OrderLogisticsChangedStatusEvent::class
    ];

    public function entity() : MorphTo
    {
        return $this->morphTo();
    }

    public function changeStatus(LogisticsStatusEnum $status) : void
    {
        $this->status = $status;
        switch ($status) {

            case LogisticsStatusEnum::COLLECT: //
                $this->collect_time = now();
                break;
            case LogisticsStatusEnum::SENDING: //
                $this->shipping_time = now();

                break;
            case LogisticsStatusEnum::DISPATCH: //
                $this->dispatch_time = now();
                break;
            case LogisticsStatusEnum::SIGNED:
                $this->signed_time = now();
                break;
            default:
                break;


        }

        $this->fireModelEvent('changedStatus', false);
    }
}
