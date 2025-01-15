<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $settleReceiver) {
            if ($settleReceiver->relationLoaded('accounts')) {
                $settleReceiver->accounts->each(function (SettleReceiverAccount $account) use ($settleReceiver) {
                    $account->settle_receiver_id = $settleReceiver->id;
                });
            }
        });
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $instance->setRelation('accounts', Collection::make());
        }
        return $instance;
    }

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_settle_receivers';
    }

    protected function casts() : array
    {
        return [
            'relation_type' => SettleRelationTypeEnum::class
        ];
    }

    public function accounts() : HasMany
    {
        return $this->hasMany(SettleReceiverAccount::class, 'settle_receiver_id', 'id');
    }
}
