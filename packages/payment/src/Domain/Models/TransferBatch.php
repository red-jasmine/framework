<?php

namespace RedJasmine\Payment\Domain\Models;

use RedJasmine\Payment\Domain\Models\Extensions\TransferExtension;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 转账批次
 */
class TransferBatch extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_transfer_batches';
    }


}
