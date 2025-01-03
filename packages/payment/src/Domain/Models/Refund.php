<?php

namespace RedJasmine\Payment\Domain\Models;


use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Data\ChannelRefundData;
use RedJasmine\Payment\Domain\Data\NotifyData;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundSuccessEvent;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Generator\RefundNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Generator\TradeNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Casts\MoneyCast;
use RedJasmine\Payment\Domain\Models\Enums\NotifyBusinessTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\RefundExtension;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property Money $refundAmount
 */
class Refund extends Model
{

    public static function boot() : void
    {
        parent::boot();

        static::creating(static function (Refund $refund) {
            $refund->generateNo();
            if ($refund->relationLoaded('extension')) {
                $refund->extension->refund_id = $refund->id;
            }
        });

    }


    protected $dispatchesEvents = [
        'created'    => RefundCreatedEvent::class,
        'processing' => RefundProcessingEvent::class,
        'success'    => RefundSuccessEvent::class,
    ];

    protected $observables = [
        'created',
        'processing',
        'success'
    ];

    protected function generateNo() : void
    {
        $this->refund_no = app(RefundNumberGeneratorInterface::class)->generator(
            [
                'merchant_app_id' => $this->merchant_app_id,
                'merchant_id'     => $this->merchant_id
            ]
        );
    }

    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if ($instance->exists === false) {
            $instance->setRelation('extension', RefundExtension::make());
        }

        return $instance;
    }


    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    protected function casts() : array
    {
        return [
            'status'       => RefundStatusEnum::class,
            'create_time'  => 'datetime',
            'refund_time'  => 'datetime',
            'refundAmount' => MoneyCast::class,
        ];
    }


    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_').'payment_refunds';
    }

    public function trade() : BelongsTo
    {

        return $this->belongsTo(Trade::class, 'trade_id', 'id');
    }


    public function setGoodsDetails(array $goodDetails = []) : void
    {
        $this->extension->good_details = $goodDetails;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(RefundExtension::class, 'refund_id', 'id');
    }


    // 是否允许渠道处理
    public function isAllowProcessing() : bool
    {

        if (in_array($this->status, [RefundStatusEnum::PRE, RefundStatusEnum::ABNORMAL,], true)) {
            return true;
        }

        return false;
    }


    /**
     * @return void
     * @throws PaymentException
     */
    public function processing() : void
    {
        if (!$this->isAllowProcessing()) {
            throw new PaymentException('退款状态不允许处理', PaymentException::REFUND_STATUS_ERROR);
        }
        // 退款处理中
        $this->status = RefundStatusEnum::PROCESSING;

        $this->fireModelEvent('processing', false);

    }


    public function isAllowSuccess() : bool
    {
        if (in_array($this->status, [
            RefundStatusEnum::PRE,
            RefundStatusEnum::ABNORMAL,
            RefundStatusEnum::PROCESSING,
        ], true)) {
            return true;
        }

        return false;
    }


    /**
     * 退款成功
     *
     * @param  Carbon|null  $refundTime
     *
     * @return void
     * @throws PaymentException
     */
    public function success(?Carbon $refundTime = null) : void
    {
        // 验证状态 验证金额
        if (!$this->isAllowSuccess()) {
            throw new PaymentException('退款状态不允许处理', PaymentException::REFUND_STATUS_ERROR);
        }
        $this->refund_time = $refundTime ?? now();
        $this->status      = RefundStatusEnum::SUCCESS;
        // 设置交易数据
        $this->trade->refundSuccess($this);

        $this->fireModelEvent('success', false);
    }

    /**
     * @param  ChannelRefundData  $data
     *
     * @return void
     * @throws PaymentException
     */
    public function setChannelQueryResult(ChannelRefundData $data) : void
    {

        switch ($data->status) {
            case RefundStatusEnum::SUCCESS:
                $this->success($data->refundTime);
                break;
            case RefundStatusEnum::ABNORMAL:
                $this->abnormal();
                break;

            default:
                break;

        }

    }


    public function abnormal(?string $errorMessage) : void
    {
        $this->status                   = RefundStatusEnum::ABNORMAL;
        $this->extension->error_message = $errorMessage;
        $this->fireModelEvent('abnormal', false);
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
        $command->businessType  = NotifyBusinessTypeEnum::REFUND;
        $command->businessNo    = $this->refund_no;
        $command->notifyType    = 'trade_status_sync';

        if (blank($this->getNotifyUlr())) {
            return null;
        }
        $command->url  = $this->getNotifyUlr();
        $command->body = [
            'merchant_app_id'        => $this->merchant_app_id,
            'refund_no'              => $this->refund_no,
            'trade_no'               => $this->trade_no,
            'status'                 => $this->status->value,
            'create_time'            => $this->create_time?->format('Y-m-d H:i:s'),
            'paid_time'              => $this->refund_time?->format('Y-m-d H:i:s'),
            'subject'                => $this->subject,
            'refund_amount_currency' => $this->refundAmount->currency,
            'refund_amount_value'    => $this->refundAmount->value,
            'pass_back_params'       => $this->extension->pass_back_params,
        ];
        return $command;
    }

}
