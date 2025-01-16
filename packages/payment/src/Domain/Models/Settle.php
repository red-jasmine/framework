<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Payment\Domain\Events\Settles\SettleCreatedEvent;
use RedJasmine\Payment\Domain\Generator\SettleNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Enums\SettleStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\SettleDetail;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Settle extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public static function boot() : void
    {
        parent::boot();
        static::creating(function (Settle $settle) {
            $settle->generateNo();
        });
    }


    protected $dispatchesEvents = [
        'created' => SettleCreatedEvent::class,
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_settles';
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $this->setUniqueIds();
            $instance->setRelation('details', Collection::make());
        }
        return $instance;
    }

    protected function generateNo() : void
    {
        $this->trade_no = app(SettleNumberGeneratorInterface::class)->generator(
            [
                'merchant_app_id' => $this->merchant_app_id,
                'merchant_id'     => $this->merchant_id
            ]
        );
    }

    public function trade() : BelongsTo
    {
        return $this->belongsTo(Trade::class, 'trade_id', 'id');
    }

    protected function casts() : array
    {
        return [
            'settle_status' => SettleStatusEnum::class,
        ];
    }

    public function merchantApp() : BelongsTo
    {
        return $this->belongsTo(MerchantApp::class, 'merchant_app_id', 'id');
    }

    public function details() : HasMany
    {
        return $this->hasMany(SettleDetail::class, 'settle_no', 'settle_no');
    }

    public function setTrade(Trade $trade) : void
    {
        $this->trade_no              = $trade->trade_no;
        $this->merchant_id           = $trade->merchant_id;
        $this->merchant_app_id       = $trade->merchant_app_id;
        $this->channel_trade_no      = $trade->channel_trade_no;
        $this->system_channel_app_id = $trade->system_channel_app_id;
        $this->channel_app_id        = $trade->channel_app_id;
        $this->channel_code          = $trade->channel_code;
        $this->channel_merchant_id   = $trade->channel_merchant_id;
    }


}
