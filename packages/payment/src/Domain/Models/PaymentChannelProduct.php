<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelProductStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentChannelProduct extends Model
{

    use SoftDeletes;

    use HasOperator;


    protected $fillable = [
        'channel_code',
        'rate',
        'name',
        'code',
        'status',
    ];

    protected $casts = [
        'status' => ChannelProductStatusEnum::class,
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_products';
    }

    public function channel() : BelongsTo
    {
        return $this->belongsTo(PaymentChannel::class, 'channel_code', 'code');
    }

}
