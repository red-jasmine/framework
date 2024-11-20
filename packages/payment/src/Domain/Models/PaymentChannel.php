<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;

class PaymentChannel extends Model
{

    use SoftDeletes;


    protected $casts = [
        'status' => ChannelStatusEnum::class,
    ];

    protected $fillable = [
        'channel',
        'name',
        'status'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix') . 'payment_channels';
    }


}
