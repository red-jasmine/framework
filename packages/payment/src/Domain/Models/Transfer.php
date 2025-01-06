<?php

namespace RedJasmine\Payment\Domain\Models;

use RedJasmine\Payment\Domain\Models\Extensions\TransferExtension;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Transfer extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_transfers';
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $this->setUniqueIds();
            $instance->setRelation('extension', new TransferExtension());
        }
        return $instance;
    }

}
