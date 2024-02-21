<?php

namespace RedJasmine\Order\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Order\Enums\Orders\OrderStatusEnum;
use RedJasmine\Order\Enums\Orders\OrderTypeEnum;
use RedJasmine\Order\Enums\Orders\PaymentStatusEnum;
use RedJasmine\Order\Enums\Orders\RefundStatusEnum;
use RedJasmine\Order\Enums\Orders\ShipStatusEnum;
use RedJasmine\Order\Enums\Orders\ShipTypeEnum;
use RedJasmine\Support\Contracts\UserInterface;
use RedJasmine\Support\DataTransferObjects\UserDTO;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\WithDTO;

class Order extends Model
{
    use WithDTO;

    use HasDateTimeFormatter;

    use SoftDeletes;

    use HasOperator;


    public $incrementing = false;

    protected $fillable = [
        'order_type',
        'ship_type',
    ];

    protected $casts = [
        'order_type'      => OrderTypeEnum::class,
        'ship_type'   => ShipTypeEnum::class,
        'order_status'    => OrderStatusEnum::class,
        'payment_status'  => PaymentStatusEnum::class,
        'ship_status' => ShipStatusEnum::class,
        'refund_status'   => RefundStatusEnum::class,
        'created_time'    => 'datetime',
        'payment_time'    => 'datetime',
        'close_time'      => 'datetime',
        'consign_time'    => 'datetime',
        'collect_time'    => 'datetime',
        'dispatch_time'   => 'datetime',
        'signed_time'     => 'datetime',
        'end_time'        => 'datetime',
        'refund_time'     => 'datetime',
        'rate_time'       => 'datetime',
    ];


    public function info() : HasOne
    {
        return $this->hasOne(OrderInfo::class, 'id', 'id');
    }

    public function products() : HasMany
    {
        return $this->hasMany(OrderProduct::class, 'oid', 'id');
    }

    public function address() : HasOne
    {
        return $this->hasOne(OrderAddress::class, 'id', 'id');
    }


    public function scopeOnlySeller(Builder $query, UserInterface $seller) : Builder
    {
        return $query->where('seller_type', $seller->getType())
                     ->where('seller_id', $seller->getID());

    }

    public function seller() : Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => UserDTO::from([
                                                                          'type'     => $attributes['seller_type'],
                                                                          'id'       => $attributes['seller_id'],
                                                                          'nickname' => $attributes['seller_nickname']
                                                                      ]),
            set: fn(?UserInterface $user) => [
                'seller_type'     => $user?->getType(),
                'seller_id'       => $user?->getID(),
                'seller_nickname' => $user?->getNickname()
            ]

        );
    }

    public function scopeOnlyBuyer(Builder $query, UserInterface $buyer) : Builder
    {
        return $query->where('buyer_type', $buyer->getType())
                     ->where('buyer_id', $buyer->getID());

    }

    public function buyer() : Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => UserDTO::from([
                                                                          'type'     => $attributes['buyer_type'],
                                                                          'id'       => $attributes['buyer_id'],
                                                                          'nickname' => $attributes['buyer_nickname']
                                                                      ]),
            set: fn(?UserInterface $user) => [
                'buyer_type'     => $user?->getType(),
                'buyer_id'       => $user?->getID(),
                'buyer_nickname' => $user?->getNickname()
            ]

        );
    }

    public function guide() : Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => UserDTO::from([
                                                                          'type' => $attributes['guide_type'],
                                                                          'id'   => $attributes['guide_id'],
                                                                      ]),
            set: fn(?UserInterface $user) => [
                'guide_type' => $user?->getType(),
                'guide_id'   => $user?->getID(),
            ]

        );
    }


}
