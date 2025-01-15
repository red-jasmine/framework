<?php

namespace RedJasmine\Payment\Domain\Models;

use RedJasmine\Payment\Domain\Models\Enums\SettleRelationTypeEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * 结算接收者
 * @property string $name
 */
class SettleReceiver extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receivers';
    }

    protected function casts() : array
    {
        return [
            'relation_type' => SettleRelationTypeEnum::class
        ];
    }
}
