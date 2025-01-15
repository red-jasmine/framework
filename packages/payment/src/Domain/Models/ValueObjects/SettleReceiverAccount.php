<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;


class SettleReceiverAccount extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settle_receiver_accounts';
    }

    protected function casts() : array
    {
        return [
            'settle_account_type' => AccountTypeEnum::class,
        ];
    }
}
