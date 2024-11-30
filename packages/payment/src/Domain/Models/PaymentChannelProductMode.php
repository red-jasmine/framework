<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;

class PaymentChannelProductMode extends Model
{
    use HasOperator;

    protected $fillable = [
        'channel_code',
        'product_code',
        'method_code',
        'platform_code'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_channel_product_modes';
    }


}
