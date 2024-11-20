<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentChannelProduct extends Model
{

    use SoftDeletes;

    use HasOperator;


    protected $fillable = [
        'channel_id',
        'name',
        'code',
        'status',
    ];

    protected $casts = [
        'status' => ChannelProductStatusEnum::class,
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix') . 'payment_channel_products';
    }

}
