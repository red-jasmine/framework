<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Payment\Domain\Events\SettleReceivers\SettleReceiverCreatedEvent;
use RedJasmine\Payment\Domain\Models\Enums\AccountTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\SettleRelationTypeEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\SettleReceiverAccount;
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

    public const  ALL_CHANNEL_MERCHANT = 'ALL_CHANNEL_MERCHANT';// 所有渠道商户

    public function getTable() : string
    {
        return 'payment_settle_receivers';
    }

    protected $dispatchesEvents = [
        'created' => SettleReceiverCreatedEvent::class,
    ];

    protected function casts() : array
    {
        return [
            'relation_type' => SettleRelationTypeEnum::class,
            'cert_type'     => CertTypeEnum::class,
            'cert_no'       => 'encrypted',
            'name'          => 'encrypted',
            'account'       => 'encrypted',
            'account_type'  => AccountTypeEnum::class,
        ];
    }

}
