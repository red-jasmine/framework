<?php

namespace RedJasmine\Order\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RedJasmine\Order\Domain\Data\OrderPaymentData;
use RedJasmine\Order\Domain\Exceptions\OrderPaymentException;
use RedJasmine\Order\Domain\Models\Enums\EntityTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\Payments\AmountTypeEnum;
use RedJasmine\Order\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class OrderPayment extends Model
{

    use HasSnowflakeId;

    public $incrementing = false;

    use HasDateTimeFormatter;

    use HasOperator;

    use HasTradeParties;

    protected $casts = [
        'entity_type' => EntityTypeEnum::class,
        'amount_type' => AmountTypeEnum::class,
        'status'      => PaymentStatusEnum::class,
    ];

    public static function newModel() : static
    {
        $model     = new static();
        $model->id = $model->newUniqueId();

        return $model;
    }

    public function getTable() : string
    {
        return config('red-jasmine-order.tables.prefix', 'jasmine_') . 'order_payments';
    }


    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }


    public function entity() : MorphTo
    {
        return $this->morphTo();
    }


    public function isAllowPaid() : bool
    {
        if ($this->status !== PaymentStatusEnum::PAID) {
            return true;
        }
        return false;

    }

    public function isAllowPaying() : bool
    {
        if ($this->status !== PaymentStatusEnum::WAIT_PAY) {
            return true;
        }
        return false;

    }

    public function paying(OrderPaymentData $data) : void
    {
        if (!$this->isAllowPaying()) {
            OrderPaymentException::newFromCodes(OrderPaymentException::STATUS_NOT_ALLOW);
        }
        // 验证状态
        $this->payment_type = $data->paymentType;
        $this->payment_id   = $data->paymentId;
        $this->message      = $data->message;
        $this->status       = PaymentStatusEnum::PAYING;
        $this->fireModelEvent('paying', false);
    }

    public function isAllowFail() : bool
    {
        if (in_array($this->status, [ PaymentStatusEnum::PAYING, PaymentStatusEnum::WAIT_PAY ], true)) {
            return true;
        }
        return false;

    }

    public function fail(OrderPaymentData $data) : void
    {
        if (!$this->isAllowFail()) {
            OrderPaymentException::newFromCodes(OrderPaymentException::STATUS_NOT_ALLOW);
        }
        $this->message = $data->message;
        $this->status  = PaymentStatusEnum::FAIL;
        $this->fireModelEvent('fail', false);
    }


    public function paid(OrderPaymentData $data) : void
    {
        if (!$this->isAllowPaid()) {
            OrderPaymentException::newFromCodes(OrderPaymentException::STATUS_NOT_ALLOW);
        }
        $this->message = $data->message;
        // 验证状态
        $this->payment_type = $data->paymentType;
        $this->payment_id   = $data->paymentId;

        $this->payment_time       = $data->paymentTime;
        $this->payment_method     = $data->paymentMethod;
        $this->payment_channel    = $data->paymentChannel;
        $this->payment_channel_no = $data->paymentChannelNo;
        $this->status             = PaymentStatusEnum::PAID;

        $this->fireModelEvent('paid', false);
    }
}
