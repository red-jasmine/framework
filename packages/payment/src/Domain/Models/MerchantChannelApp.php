<?php

namespace RedJasmine\Payment\Domain\Models;


use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class MerchantChannelApp extends Model
{

    public $incrementing = false;
    use HasSnowflakeId;
    use HasOperator;


    protected $fillable = [
        'merchant_id',
        'channel_app_id'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_merchant_channel_apps';
    }


}
