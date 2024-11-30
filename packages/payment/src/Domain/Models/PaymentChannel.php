<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\ChannelStatusEnum;

class PaymentChannel extends Model
{

    use SoftDeletes;


    protected $casts = [
        'status' => ChannelStatusEnum::class,
    ];

    protected $fillable = [
        'code',
        'name',
        'status'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channels';
    }

    public function products() : HasMany
    {
        return $this->hasMany(PaymentChannelProduct::class, 'channel_code', 'code');
    }


    public function apps() : HasMany
    {
        return $this->hasMany(PaymentChannelApp::class, 'channel_code', 'code');
    }


}
