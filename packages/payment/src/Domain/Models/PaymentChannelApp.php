<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelAppStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentChannelApp extends Model
{

    use HasOwner;


    public $incrementing = false;

    use HasSnowflakeId;


    use SoftDeletes;

    use HasOperator;


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix') . 'payment_channel_apps';
    }

    protected $casts = [
        'status' => ChannelAppStatusEnum::class,
    ];
}
