<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class PaymentTrade extends Model
{

    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    use SoftDeletes;

    protected $casts = [
        'status' => TradeStatusEnum::class,
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix') . 'payment_trades';
    }

    /**
     * Generate unique keys for the model.
     *
     * @return void
     */
    public function setUniqueIds() : void
    {
        parent::setUniqueIds();
        $this->extension->{$this->extension->getKeyName()} = $this->{$this->getKeyName()};
    }


    public static function newModel() : static
    {
        $model = new static();
        $model->setRelation('extension', new PaymentTradeExtension());
        return $model;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(PaymentTradeExtension::class, 'id', 'id');
    }

    public function setMerchantApp(PaymentMerchantApp $merchantApp) : void
    {
        $this->merchant_app_id = $merchantApp->id;
        $this->merchant_id     = $merchantApp->merchant_id;
    }


    public function preCreate():void
    {
        $this->status = TradeStatusEnum::PRE;
    }


    public function merchantApp() : BelongsTo
    {
        return $this->belongsTo(PaymentMerchantApp::class,'id','merchant_app_id');
    }

}
