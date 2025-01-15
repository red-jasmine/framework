<?php

namespace RedJasmine\Payment\Domain\Models;

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




    protected function casts() : array
    {
        return [

        ];
    }
}
