<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Payment\Domain\Events\Trades\TradePayingEvent;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property Money $amount
 * @property Money $paymentAmount
 */
class Trade extends Model
{

    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    protected $casts = [
        'status'      => TradeStatusEnum::class,
        'create_time' => 'datetime',
        'pay_time'    => 'datetime',
        'paid_time'   => 'datetime',
        'notify_time' => 'datetime',
        'refund_time' => 'datetime',
        'settle_time' => 'datetime',
    ];

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_trades';
    }

    protected function amount() : Attribute
    {
        return Attribute::make(get: static fn($value, array $attributes) => new Money(
            $attributes['amount_value'] ?? 0,
            $attributes['amount_currency'] ?? null
        ),
            set: static fn(Money $value, array $attributes) => [
                'amount_value'    => $value->value,
                'amount_currency' => $value->currency,
            ],
        );
    }

    protected $dispatchesEvents = [
        'paying' => TradePayingEvent::class,
        'paid'   => TradePaidEvent::class,
    ];

    protected $observables = [
        'paying',
        'paid',
    ];


    protected function paymentAmount() : Attribute
    {
        return Attribute::make(get: static fn($value, array $attributes) => new Money(
            $attributes['payment_amount_value'] ?? 0,
            $attributes['payment_amount_currency'] ?? null
        ),
            set: static fn(Money $value, array $attributes) => [
                'payment_amount_value'    => $value->value,
                'payment_amount_currency' => $value->currency,
            ],
        );
    }


    protected function payer() : Attribute
    {
        return Attribute::make(get: static fn($value, array $attributes) => Payer::from([
                                                                                            'type'    => $attributes['payer_type'],
                                                                                            'account' => $attributes['payer_account'],
                                                                                            'name'    => $attributes['payer_name'],
                                                                                            'user_id' => $attributes['payer_user_id'],
                                                                                            'open_id' => $attributes['payer_open_id'],
                                                                                        ])
            ,
            set: static fn(Payer $value, array $attributes) => [
                'payer_type'    => $value->type,
                'payer_account' => $value->account,
                'payer_name'    => $value->name,
                'payer_user_id' => $value->userId,
                'payer_open_id' => $value->openId,
            ],
        );
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
        $this->status      = TradeStatusEnum::PRE;
        $this->create_time = now();
    }


    public function merchantApp() : BelongsTo
    {
        return $this->belongsTo(MerchantApp::class, 'merchant_app_id', 'id');
    }


    protected function isAllowPaying() : bool
    {
        if (in_array($this->status, [ TradeStatusEnum::PRE ], true)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param ChannelApp $channelApp
     * @param Environment $environment
     * @param ChannelTradeData $channelTrade
     * @return void
     * @throws PaymentException
     */
    public function paying(ChannelApp $channelApp, Environment $environment, ChannelTradeData $channelTrade) : void
    {
        if (!$this->isAllowPaying()) {
            throw new PaymentException('支付状态错误', PaymentException::TRADE_STATUS_ERROR);
        }
        $this->status                 = TradeStatusEnum::PAYING;
        $this->payment_channel_app_id = $channelApp->id;
        $this->channel_code           = $channelTrade->channelCode;
        $this->channel_app_id         = $channelTrade->channelAppId;
        $this->channel_merchant_id    = $channelTrade->channelMerchantId;
        $this->channel_product_code   = $channelTrade->channelProductCode;
        $this->scene_code             = $channelTrade->sceneCode;
        $this->method_code            = $channelTrade->methodCode;
        $this->channel_trade_no       = $channelTrade->channelTradeNo;
        $this->paying_time            = now();

        $this->extension->device = $environment->device?->toArray();
        $this->extension->client = $environment->client?->toArray();
        $this->fireModelEvent('paying', false);
    }


    public function isAllowPaid() : bool
    {

        if (in_array($this->status, [ TradeStatusEnum::PRE, TradeStatusEnum::PAYING ], true)) {
            return true;
        }
        return false;
    }

    /**
     * 支付成功
     * @param ChannelTradeData $channelTrade
     * @return void
     * @throws PaymentException
     */
    public function paid(ChannelTradeData $channelTrade) : void
    {

        if (!$this->isAllowPaid()) {
            throw new PaymentException('支付状态错误', PaymentException::TRADE_STATUS_ERROR);
        }
        if (!$this->amount->equal($channelTrade->paymentAmount)) {
            throw new PaymentException('支付金额不一致', PaymentException::TRADE_AMOUNT_ERROR);
        }

        $this->status              = TradeStatusEnum::SUCCESS;
        $this->channel_code        = $channelTrade->channelCode;
        $this->channel_app_id      = $channelTrade->channelAppId;
        $this->channel_merchant_id = $channelTrade->channelMerchantId;
        $this->channel_trade_no    = $channelTrade->channelTradeNo;
        $this->paid_time           = $channelTrade->paidTime;
        $this->paymentAmount       = $channelTrade->paymentAmount;
        $this->payer               = $channelTrade->payer;

        $this->fireModelEvent('paid', false);


    }

}
