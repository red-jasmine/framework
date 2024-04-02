<?php

namespace RedJasmine\Order\Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Domain\Order\Models\ValueObjects\PriceCasts;
use RedJasmine\Order\Models\HasTradeParties;
use RedJasmine\Order\Models\OrderProductCardKey;
use RedJasmine\Order\Domain\Order\Enums\OrderStatusEnum;
use RedJasmine\Order\Domain\Order\Enums\PaymentStatusEnum;
use RedJasmine\Order\Domain\Order\Enums\RefundStatusEnum;
use RedJasmine\Order\Domain\Order\Enums\ShippingStatusEnum;
use RedJasmine\Order\Domain\Order\Enums\ShippingTypeEnum;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use Spatie\LaravelData\WithData;

class OrderProduct extends Model
{
    use WithData;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;

    use HasTradeParties;


    public $incrementing = false;


    protected $casts = [
        'shipping_type'   => ShippingTypeEnum::class,
        'order_status'    => OrderStatusEnum::class,
        'shipping_status' => ShippingStatusEnum::class,
        'payment_status'  => PaymentStatusEnum::class,
        'refund_status'   => RefundStatusEnum::class,
        //'price'           => PriceCasts::class
    ];

    protected $fillable = [
        'shipping_type',
        'product_type',
        'product_id',
        'sku_id',
        'num',
        'price',
    ];


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function info() : HasOne
    {
        return $this->hasOne(OrderProductInfo::class, 'id', 'id');
    }


    public function cardKeys() : HasMany
    {
        return $this->hasMany(OrderProductCardKey::class, 'order_product_id', 'id');
    }


}
