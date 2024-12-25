<?php

namespace RedJasmine\Payment\Domain\Models;


use RedJasmine\Payment\Domain\Models\Enums\NotifyStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Notify extends Model
{

    use HasSnowflakeId;

    public $incrementing = false;


    protected $casts = [
        'status'          => NotifyStatusEnum::class,
        'notify_request'  => 'array',
        'notify_response' => 'array'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_notifies';
    }
}
