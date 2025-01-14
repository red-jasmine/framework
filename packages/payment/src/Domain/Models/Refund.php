<?php

namespace RedJasmine\Payment\Domain\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Data\ChannelRefundData;
use RedJasmine\Payment\Domain\Data\NotifyData;
use RedJasmine\Payment\Domain\Events\Refunds\RefundAbnormalEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCancelEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCloseEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundCreatedEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundExecutingEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundFailEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundProcessingEvent;
use RedJasmine\Payment\Domain\Events\Refunds\RefundSuccessEvent;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Generator\RefundNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Casts\MoneyCast;
use RedJasmine\Payment\Domain\Models\Enums\NotifyBusinessTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\RefundExtension;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

/**
 * @property $status
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

    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    protected function casts() : array
    {
        return [
            'refund_status' => RefundStatusEnum::class,
            'create_time'   => 'datetime',
            'refund_time'   => 'datetime',
            'refundAmount'  => MoneyCast::class,
        ];
    }


    protected $dispatchesEvents = [
        'created'    => RefundCreatedEvent::class,
        'executing'  => RefundExecutingEvent::class,
        'processing' => RefundProcessingEvent::class,
        'fail'       => RefundFailEvent::class,
        'success'    => RefundSuccessEvent::class,
        'abnormal'   => RefundAbnormalEvent::class,
        'close'      => RefundCloseEvent::class,
        'cancel'     => RefundCancelEvent::class,
    ];

    protected $observables = [
        'created',
        'executing',
        'processing',
        'abnormal',
        'fail',
        'close',
        'cancel',
        'success',
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


    public function isAllowExecuting() : bool
    {

        if (in_array($this->refund_status, [
            RefundStatusEnum::PRE,
        ], true)) {
            return true;
        }

        return false;
    }

    /**
     * 分步执行
     * @return void
     * @throws PaymentException
     */
    public function executing() : void
    {
        if (!$this->isAllowExecuting()) {
            throw new PaymentException('状态错误', PaymentException::REFUND_STATUS_ERROR);
        }
        $this->refund_status = RefundStatusEnum::PENDING;

        $this->fireModelEvent('executing', false);
    }


    // 是否允许渠道处理
    public function isAllowProcessing() : bool
    {

        if (in_array($this->refund_status, [
            RefundStatusEnum::PRE,
            RefundStatusEnum::ABNORMAL,
        ], true)) {
            return true;
        }

        return false;
    }


    /**
     * 外部调用执行成功
     * @return void
     * @throws PaymentException
     */
    public function processing() : void
    {
        if (!$this->isAllowProcessing()) {
            throw new PaymentException('退款状态不允许处理', PaymentException::REFUND_STATUS_ERROR);
        }
        // 退款处理中
        $this->refund_status = RefundStatusEnum::PROCESSING;

        $this->fireModelEvent('processing', false);

    }


    public function isAllowSuccess() : bool
    {
        if (in_array($this->refund_status, [
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
        $this->refund_time   = $refundTime ?? now();
        $this->refund_status = RefundStatusEnum::SUCCESS;
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

        switch ($data->refund_status) {
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

    public function isAllowAbnormal() : bool
    {
        if (in_array($this->refund_status, [
            RefundStatusEnum::FAIL,
            RefundStatusEnum::ABNORMAL,
            RefundStatusEnum::PROCESSING,
        ], true)) {
            return true;
        }

        return false;
    }

    /**
     * @param  string|null  $errorMessage
     *
     * @return void
     * @throws PaymentException
     */
    public function abnormal(?string $errorMessage) : void
    {
        if (!$this->isAllowAbnormal()) {
            throw new PaymentException('状态错误');
        }
        $this->refund_status            = RefundStatusEnum::ABNORMAL;
        $this->extension->error_message = $errorMessage;
        $this->fireModelEvent('abnormal', false);
    }

    public function isAllowFail() : bool
    {
        if (in_array($this->refund_status, [
            RefundStatusEnum::FAIL,
            RefundStatusEnum::ABNORMAL,
            RefundStatusEnum::PROCESSING,
        ], true)) {
            return true;
        }

        return false;
    }

    /**
     * @param  string|null  $message
     *
     * @return void
     * @throws PaymentException
     */
    public function fail(?string $message = null) : void
    {
        if (!$this->isAllowFail()) {
            throw new PaymentException('状态错误');
        }
        $this->refund_status            = RefundStatusEnum::FAIL;
        $this->extension->error_message = $message;
        $this->fireModelEvent('fail', false);
    }

    public function isAllowCancel() : bool
    {
        if (in_array($this->refund_status, [
            RefundStatusEnum::PRE,
            RefundStatusEnum::FAIL,
        ], true)) {
            return true;
        }

        return false;
    }

    public function cancel() : void
    {
        if (!$this->isAllowCancel()) {
            throw new PaymentException('状态错误');
        }
        $this->refund_status = RefundStatusEnum::CANCEL;
        $this->fireModelEvent('cancel', false);
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
            'status'                 => $this->refund_status->value,
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
