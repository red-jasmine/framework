<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\MerchantAppStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Helpers\Encrypter\AES;

class PaymentMerchantApp extends Model
{

    public $incrementing = false;
    use HasSnowflakeId;

    use SoftDeletes;

    use HasOperator;

    protected $casts = [
        'status'                  => MerchantAppStatusEnum::class,
    ];

    protected $fillable = [
        'merchant_id',
        'name',
        'status'
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix') . 'payment_merchant_apps';
    }

}

