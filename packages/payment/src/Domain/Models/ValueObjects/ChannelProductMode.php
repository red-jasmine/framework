<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Payment\Domain\Models\Enums\ModeStatusEnum;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\Channel;
use RedJasmine\Payment\Domain\Models\ChannelProduct;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class ChannelProductMode extends Model
{

    use HasOperator;

    protected $fillable = [
        'payment_channel_product_id',
        'platform_code',
        'method_code',
        'status',

    ];

    protected $casts = [
        'status' => ModeStatusEnum::class
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes';
    }


    public function product() : BelongsTo
    {
        return $this->belongsTo(ChannelProduct::class, 'payment_channel_product_id', 'id');
    }


}
