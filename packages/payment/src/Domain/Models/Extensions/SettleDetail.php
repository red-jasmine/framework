<?php

namespace RedJasmine\Payment\Domain\Models\Extensions;

use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SettleStatusEnum;
use RedJasmine\Payment\Domain\Models\Model;
use RedJasmine\Payment\Domain\Models\SettleReceiver;
use RedJasmine\Support\Domain\Casts\MoneyOldCast;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class SettleDetail extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_settle_details';
    }


    protected function casts() : array
    {
        return [
            'amount'        => MoneyOldCast::class,
            'account_type'  => AccountTypeEnum::class,
            'cert_type'     => CertTypeEnum::class,
            'settle_status' => SettleStatusEnum::class,
        ];
    }


    public function setSettleReceiver(SettleReceiver $settleReceiver) : void
    {
        $this->settle_receiver_id = $settleReceiver->id;
        $this->name               = $settleReceiver->name;
        $this->account_type       = $settleReceiver->account_type;
        $this->account            = $settleReceiver->account;
        $this->cert_type          = $settleReceiver->cert_type;
        $this->cert_no            = $settleReceiver->cert_no;
    }


}
