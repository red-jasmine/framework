<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\MerchantStatusEnum;
use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Merchant extends Model implements OwnerInterface
{


    public $incrementing = false;

    public $uniqueShortId = true;

    use HasSnowflakeId;

    use HasOwner;


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

    protected function casts() : array
    {
        return [

            'status' => MerchantStatusEnum::class,
        ];
    }


    public function getTable() : string
    {
        return 'payment_merchants';
    }


    public function setStatus(MerchantStatusEnum $status) : void
    {

        $this->status = $status;

        $this->fireModelEvent('changeStatus', false);

    }




}
