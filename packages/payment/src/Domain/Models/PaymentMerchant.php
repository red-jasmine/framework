<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentMerchant extends Model
{


    public $incrementing = false;
    use HasOwner;

    use HasSnowflakeId;
    use HasOperator;

    use SoftDeletes;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'status',
        'name',
        'short_name',
        'type',
    ];

    public $casts = [

        'status' => MerchantStatusEnum::class,
    ];


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix','jasmine_') . 'payment_merchants';
    }


    public static function newModel() : static
    {
        $model = new static();


        return $model;
    }


    public function setStatus(MerchantStatusEnum $status) : void
    {

        $this->status = $status;

        $this->fireModelEvent('changeStatus', false);

    }
}
