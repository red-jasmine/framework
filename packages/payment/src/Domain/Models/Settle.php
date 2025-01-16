<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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


}
