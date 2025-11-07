<?php

namespace RedJasmine\Payment\Domain\Models;


use RedJasmine\Money\Data\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RedJasmine\Payment\Domain\Contracts\AsyncNotifyInterface;
use RedJasmine\Payment\Domain\Data\ChannelTradeData;
use RedJasmine\Payment\Domain\Data\NotifyData;
use RedJasmine\Payment\Domain\Events\Trades\TradePaidEvent;
use RedJasmine\Payment\Domain\Events\Trades\TradePayingEvent;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Models\Enums\NotifyBusinessTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\TradeExtension;
use RedJasmine\Payment\Domain\Models\ValueObjects\Environment;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;


/**
 * @property Money $amount
 * @property Money $paymentAmount
 * @property Money $refundAmount
 * @property Money $refundingAmount
 * @property string $biz
 */
class Trade extends Model implements AsyncNotifyInterface, UniqueNoInterface
{


    public static $uniqueNoKey = 'trade_no';
    use HasUniqueNo;

    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public static function boot() : void
    {
        parent::boot();
        static::creating(function (Trade $trade) {
            if ($trade->relationLoaded('extension')) {
                $trade->extension->trade_id = $trade->id;
            }
        });
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $instance->setUniqueIds();
            $instance->setRelation('extension', new TradeExtension());
        }
        return $instance;
    }


    protected function buildUniqueNoFactors() : array
    {
        return [
            'merchant_app_id' => $this->merchant_app_id,
            'merchant_id'     => $this->merchant_id
        ];
    }


    protected function casts() : array
    {
        return [
            'status'          => TradeStatusEnum::class,
            'create_time'     => 'datetime',
            'pay_time'        => 'datetime',
            'paid_time'       => 'datetime',
            'notify_time'     => 'datetime',
            'refund_time'     => 'datetime',
            'settle_time'     => 'datetime',
            'amount'          => MoneyCast::class,
            'paymentAmount'   => MoneyCast::class,
            'refundAmount'    => MoneyCast::class,
            'refundingAmount' => MoneyCast::class.':refund_amount_currency',

        ];
    }


    public function getTable() : string
    {
        return 'payment_trades';
    }

    protected $dispatchesEvents = [
        'paying' => TradePayingEvent::class,
        'paid'   => TradePaidEvent::class,
    ];

    protected $observables = [
        'paying',
        'paid',
    ];

    protected function payer() : Attribute
    {
        return Attribute::make(get: fn($value, array $attributes) => Payer::from([
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

    public function extension() : HasOne
    {
        return $this->hasOne(TradeExtension::class, 'trade_id', 'id');
    }


    /**
     * 获取与此交易相关的所有退款
     *
     * 此方法建立了一个一对多的关系，表示一个交易可以有多个退款
     * 它通过 'trade_id' 字段与Refund模型中的'id'字段进行关联
     *
     * @return HasMany 代表与此交易相关的退款集合
     */
    public function refunds() : HasMany
    {
        return $this->hasMany(Refund::class, 'trade_id', 'id');
    }


    public function setMerchantApp(MerchantApp $merchantApp) : void
    {
        $this->merchant_app_id = $merchantApp->id;
        $this->merchant_id     = $merchantApp->merchant_id;
    }


    public function preCreate() : void
    {
        $this->status       = TradeStatusEnum::PRE;
        $this->refundAmount = new Money(0, $this->amount->getCurrency());
        $this->create_time  = now();

    }


    public function merchantApp() : BelongsTo
    {
        return $this->belongsTo(MerchantApp::class, 'merchant_app_id', 'id');
    }


    protected function isAllowPaying() : bool
    {
        if (in_array($this->status, [TradeStatusEnum::PRE], true)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param  ChannelApp  $channelApp
     * @param  Environment  $environment
     * @param  ChannelTradeData  $channelTrade
     *
     * @return void
     * @throws PaymentException
     */
    public function paying(ChannelApp $channelApp, Environment $environment, ChannelTradeData $channelTrade) : void
    {
        if (!$this->isAllowPaying()) {
            throw new PaymentException('支付状态错误', PaymentException::TRADE_STATUS_ERROR);
        }
        $this->status                = TradeStatusEnum::PAYING;
        $this->system_channel_app_id = $channelApp->id;
        $this->channel_code          = $channelTrade->channelCode;
        $this->channel_app_id        = $channelTrade->channelAppId;
        $this->channel_merchant_id   = $channelTrade->channelMerchantId;
        $this->channel_product_code  = $channelTrade->channelProductCode;
        $this->scene_code            = $channelTrade->sceneCode;
        $this->method_code           = $channelTrade->methodCode;
        $this->channel_trade_no      = $channelTrade->channelTradeNo;
        $this->paying_time           = now();
        $this->extension->device     = $environment->device?->toArray();
        $this->extension->client     = $environment->client?->toArray();
        $this->fireModelEvent('paying', false);
    }


    public function isAllowPaid() : bool
    {

        if (in_array($this->status, [TradeStatusEnum::PRE, TradeStatusEnum::PAYING], true)) {
            return true;
        }
        return false;
    }

    public function isPaid() : bool
    {

        if (in_array($this->status, [TradeStatusEnum::SUCCESS, TradeStatusEnum::FINISH], true)) {
            return true;
        }
        return false;

    }

    /**
     * 支付成功
     *
     * @param  ChannelTradeData  $channelTrade
     *
     * @return void
     * @throws PaymentException
     */
    public function paid(ChannelTradeData $channelTrade) : void
    {

        if (!$this->isAllowPaid()) {
            throw new PaymentException('支付状态错误', PaymentException::TRADE_STATUS_ERROR);
        }

        if (!$this->amount->equals($channelTrade->paymentAmount)) {
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

    /**
     * 创建退款单
     *
     * @param  Refund  $refund
     *
     * @return void
     * @throws PaymentException
     */
    public function createRefund(Refund $refund) : void
    {

        // 验证支付状态
        if ($this->status !== TradeStatusEnum::SUCCESS) {
            throw new PaymentException('支付状态错误', PaymentException::TRADE_STATUS_ERROR);
        }
        // 验证 退款金额 和 不能超过订单金额
        if ($this->amount->compare($refund->refundAmount->add($this->refundAmount ?? new Money())) < 0) {
            throw new PaymentException('退款金额不能超过订单金额', PaymentException::TRADE_REFUND_AMOUNT_ERROR);
        }

        if ($this->amount->compare($this->refundingAmount->add($refund->refundAmount)) < 0) {
            throw new PaymentException('退款金额不能超过付款金额', PaymentException::TRADE_REFUND_AMOUNT_ERROR);
        }
        // 退款 时间不能超过支付时间一年
        if ($this->paid_time->diffInYears(now()) > 1) {
            throw new PaymentException('退款时间不能超过支付时间一年', PaymentException::TRADE_REFUND_TIME_ERROR);
        }


        //
        $refund->biz                     = $this->biz;
        $refund->merchant_id             = $this->merchant_id;
        $refund->trade_id                = $this->id;
        $refund->trade_no                = $this->trade_no;
        $refund->merchant_app_id         = $this->merchant_app_id;
        $refund->merchant_trade_no       = $this->merchant_trade_no;
        $refund->merchant_trade_order_no = $this->merchant_trade_order_no;
        $refund->channel_code            = $this->channel_code;
        $refund->channel_trade_no        = $this->channel_trade_no;
        $refund->channel_app_id          = $this->channel_app_id;
        $refund->channel_merchant_id     = $this->channel_merchant_id;
        $refund->system_channel_app_id   = $this->system_channel_app_id;
        $refund->refund_status           = RefundStatusEnum::PRE;


        $this->refundingAmount = $this->refundingAmount->add($refund->refundAmount);

        if (!$this->relationLoaded('refunds')) {
            $this->setRelation('refunds', Collection::make([]));
        }
        $this->refunds->add($refund);

    }


    public function refundSuccess(Refund $refund) : void
    {
        $this->refundingAmount = $this->refundingAmount->subtract($refund->refundAmount);
        $this->refundAmount    = $this->refundAmount->add($refund->refundAmount);

        $this->refund_time = $this->refund_time ?? $refund->refund_time;
        // 如果退款金额 等于 支付金额 那么支付状态为完成
        if ($this->refundAmount->compare($this->amount) > 0) {
            $this->status = TradeStatusEnum::REFUND;
        }
    }


    public function getNotifyUlr() : ?string
    {
        return $this->extension->notify_url;
    }

    public function getAsyncNotify() : ?NotifyData
    {
        $command                = new NotifyData();
        $command->merchantId    = $this->merchant_id;
        $command->merchantAppId = $this->merchant_app_id;
        $command->businessType  = NotifyBusinessTypeEnum::TRADE;
        $command->businessNo    = $this->trade_no;
        $command->notifyType    = 'trade_status_sync';

        if (blank($this->getNotifyUlr())) {
            return null;
        }
        $command->url  = $this->getNotifyUlr();
        $command->body = [
            'merchant_app_id'         => $this->merchant_app_id,
            'trade_no'                => $this->trade_no,
            'merchant_trade_no'       => $this->merchant_trade_no,
            'merchant_trade_order_no' => $this->merchant_trade_order_no,
            'channel_code'            => $this->channel_code,
            'method_code'             => $this->method_code,
            'status'                  => $this->status->value,
            'create_time'             => $this->create_time?->format('Y-m-d H:i:s'),
            'paid_time'               => $this->paid_time?->format('Y-m-d H:i:s'),
            'subject'                 => $this->subject,
            'amount_currency'         => $this->amount->getCurrency(),
            'amount_amount'           => $this->amount->getAmount(),
            'pass_back_params'        => $this->extension->pass_back_params,
        ];
        return $command;
    }

}
