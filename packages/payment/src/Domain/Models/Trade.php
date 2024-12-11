<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Trade extends Model
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
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades';
    }

    public function setAmount(Money $money) : void
    {
        $this->amount_currency = $money->currency;
        $this->amount_value    = $money->amount;

    }

    public function setGoodsDetails(array $goodDetails = []) : void
    {
        $this->extension->good_details = $goodDetails;

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
        $model->setRelation('extension', new TradeExtension());
        return $model;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(TradeExtension::class, 'id', 'id');
    }

    public function setMerchantApp(MerchantApp $merchantApp) : void
    {
        $this->merchant_app_id = $merchantApp->id;
        $this->merchant_id     = $merchantApp->merchant_id;
    }


    public function preCreate() : void
    {
        $this->status = TradeStatusEnum::PRE;
    }


    public function merchantApp() : BelongsTo
    {
        return $this->belongsTo(MerchantApp::class, 'id', 'merchant_app_id');
    }

}
