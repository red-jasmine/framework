<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property Money $amount
 */
class Trade extends Model
{

    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    protected $casts = [
        'status' => TradeStatusEnum::class,
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades';
    }

    public function setAmountAttribute(Money $money) : void
    {
        $this->amount_currency = $money->currency;
        $this->amount_value    = $money->value;
    }

    public function getAmountAttribute() : Money
    {
        return new Money($this->amount_value, $this->amount_currency);
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
        return $this->belongsTo(MerchantApp::class, 'merchant_app_id', 'id');
    }


    /**
     *
     * @param Environment $environment
     * @param ChannelTradeData $channelTrade
     * @return void
     */
    public function paying(Environment $environment, ChannelTradeData $channelTrade) : void
    {
        $this->status               = TradeStatusEnum::PAYING;
        $this->channel_code         = $channelTrade->channelCode;
        $this->channel_app_id       = $channelTrade->channelAppId;
        $this->channel_product_code = $channelTrade->channelProductCode;
        $this->channel_merchant_id  = $channelTrade->channelMerchantId;
        $this->scene_code           = $channelTrade->sceneCode;
        $this->method_code          = $channelTrade->methodCode;
        $this->channel_trade_no     = $channelTrade->channelTradeNo;

        $this->extension->device = $environment->device?->toArray();
        $this->extension->client = $environment->client?->toArray();

    }

}
