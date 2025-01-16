<?php

namespace RedJasmine\Payment\Domain\Models\Extensions;

use RedJasmine\Payment\Domain\Models\Enums\SettleStatusEnum;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class SettleDetail extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_details';
    }


    protected function casts() : array
    {
        return [
            'settle_status' => SettleStatusEnum::class,
        ];
    }


}
