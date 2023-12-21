<?php

namespace RedJasmine\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Contracts\ProductInterface;
use RedJasmine\Order\Enums\Orders\OrderStatusEnums;
use RedJasmine\Order\Enums\Orders\PaymentStatusEnums;
use RedJasmine\Order\Enums\Orders\ShippingStatusEnums;
use RedJasmine\Order\Enums\Orders\ShippingTypeEnums;
use RedJasmine\Order\Services\Orders\OrderProductAble;
use RedJasmine\Support\Traits\HasDateTimeFormatter;

class OrderProduct extends Model implements ProductInterface
{
    use HasDateTimeFormatter;

    use SoftDeletes;

    use OrderProductAble;

    public $incrementing = false;

    protected $casts = [
        'shipping_type'   => ShippingTypeEnums::class,
        'order_status'    => OrderStatusEnums::class,
        'shipping_status' => ShippingStatusEnums::class,
        'payment_status'  => PaymentStatusEnums::class,
    ];


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'oid', 'id');
    }


}
