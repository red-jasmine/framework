<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use RedJasmine\Payment\Domain\Data\ChannelTransferData;
use RedJasmine\Payment\Domain\Data\TransferPayee;
use RedJasmine\Payment\Domain\Events\Transfers\TransferAbnormalEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferCreatedEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferExecutingEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferProcessingEvent;
use RedJasmine\Payment\Domain\Events\Transfers\TransferSuccessEvent;
use RedJasmine\Payment\Domain\Exceptions\PaymentException;
use RedJasmine\Payment\Domain\Generator\TransferNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Casts\MoneyCast;
use RedJasmine\Payment\Domain\Models\Enums\CertTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\IdentityTypeEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferSceneEnum;
use RedJasmine\Payment\Domain\Models\Enums\TransferStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\TransferExtension;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;

class Transfer extends Model
{
    public $incrementing = false;

    use HasSnowflakeId;

    use HasOperator;

    public static function boot() : void
    {
        parent::boot();
        static::creating(static function (Transfer $transfer) {
            $transfer->generateNo();
            if ($transfer->relationLoaded('extension')) {
                $transfer->extension->transfer_id = $transfer->id;
            }
        });
    }

    public function getTable() : string
    {
        return config('red-jasmine-payment.tables.prefix', 'jasmine_') . 'payment_transfers';
    }

    protected function casts() : array
    {
        return [
            'transfer_status'     => TransferStatusEnum::class,
            'scene_code'          => TransferSceneEnum::class,
            'amount'              => MoneyCast::class,
            'payee_identity_type' => IdentityTypeEnum::class,
            'payee_cert_type'     => CertTypeEnum::class,
            'transfer_time'       => 'datetime',
            'processing_time'     => 'datetime'
        ];
    }

    protected $dispatchesEvents = [
        'created'    => TransferCreatedEvent::class,
        'success'    => TransferSuccessEvent::class,
        'executing'  => TransferExecutingEvent::class,
        'processing' => TransferProcessingEvent::class,
        'abnormal'   => TransferAbnormalEvent::class,
    ];

    protected $observables = [
        'created',
        'success',
        'executing',
        'processing',
        'abnormal',
    ];

    protected function generateNo() : void
    {
        $this->transfer_no = app(TransferNumberGeneratorInterface::class)->generator(
            [
                'merchant_app_id' => $this->merchant_app_id,
                'merchant_id'     => $this->merchant_id
            ]
        );
    }


    public function newInstance($attributes = [], $exists = false) : static
    {
        $instance = parent::newInstance($attributes, $exists);
        if (!$instance->exists) {
            $this->setUniqueIds();
            $instance->setRelation('extension', new TransferExtension());
        }
        return $instance;
    }


    public function extension() : HasOne
    {
        return $this->hasOne(TransferExtension::class, 'transfer_id', 'id');
    }


    /**
     *
     * @return Attribute
     */
    public function payee() : Attribute
    {
        return Attribute::make(
            get: static function (mixed $value, array $attributes) {
                return TransferPayee::from([
                                               'identityType' => $attributes['payee_identity_type'],
                                               'identityId'   => $attributes['payee_identity_id'],
                                               'name'         => $attributes['payee_name'],
                                               'certType'     => $attributes['payee_cert_type'],
                                               'certNo'       => $attributes['payee_cert_no'],
                                           ]);
            },
            set: static function (TransferPayee $payee) {
                $attributes                        = [];
                $attributes['payee_identity_type'] = $payee->identityType->value;
                $attributes['payee_identity_id']   = $payee->identityId;
                $attributes['payee_name']          = $payee->name;
                $attributes['payee_cert_type']     = $payee->certType?->value;
                $attributes['payee_cert_no']       = $payee->certNo;
                return $attributes;
            },

        );
    }


    public function setChannelApp(ChannelApp $channelApp, ChannelProduct $channelProduct) : void
    {

        $this->system_channel_app_id = $channelApp->id;
        $this->channel_code           = $channelApp->channel_code;
        $this->channel_merchant_id    = $channelApp->channel_merchant_id;
        $this->channel_app_id         = $channelApp->channel_app_id;
        $this->channel_product_code   = $channelProduct->code;

    }

    public function isAllowExecuting() : bool
    {
        if (!in_array($this->transfer_status,
            [
                TransferStatusEnum::PRE,
                TransferStatusEnum::FAIL,
            ], true
        )) {
            return false;
        }
        return true;
    }


    public function isAllowCancel() : bool
    {
        if ($this->transfer_status !== TransferStatusEnum::PRE) {
            return false;
        }
        return true;
    }


    /**
     * @return void
     * @throws PaymentException
     */
    public function cancel() : void
    {
        if (!$this->isAllowCancel()) {
            throw new PaymentException('状态错误');
        }
        $this->transfer_status = TransferStatusEnum::CANCEL;
        $this->executing_time  = now();
        $this->fireModelEvent('cancel', false);

    }


    /**
     * @return void
     * @throws PaymentException
     */
    public function executing() : void
    {
        if (!$this->isAllowExecuting()) {
            throw new PaymentException('转账不允许执行');
        }
        $this->transfer_status = TransferStatusEnum::PENDING;
        $this->executing_time  = now();
        $this->fireModelEvent('executing', false);
    }


    public function isAllowSuccess() : bool
    {
        if (!in_array($this->transfer_status,
            [
                TransferStatusEnum::FAIL,
                TransferStatusEnum::PROCESSING,
            ], true
        )) {
            return false;
        }
        return true;
    }

    /**
     * @param ChannelTransferData $data
     *
     * @return void
     * @throws PaymentException
     */
    public function success(ChannelTransferData $data) : void
    {
        if (!$this->isAllowSuccess()) {
            throw new PaymentException('状态不一致');
        }
        $this->channel_transfer_no = $data->channelTransferNo;
        $this->transfer_status     = TransferStatusEnum::SUCCESS;
        $this->transfer_time       = $data->transferTime ?? now();

        $this->fireModelEvent('success', false);
    }

    public function isAllowFail() : bool
    {
        if (!in_array($this->transfer_status,
            [
                TransferStatusEnum::FAIL,
                TransferStatusEnum::PRE,
                TransferStatusEnum::PENDING,
                TransferStatusEnum::PROCESSING,
            ], true
        )) {
            return false;
        }
        return true;
    }

    /**
     * @param ChannelTransferData $data
     *
     * @return void
     * @throws PaymentException
     */
    public function fail(ChannelTransferData $data) : void
    {
        if (!$this->isAllowFail()) {
            throw new PaymentException('状态不一致');
        }
        $this->channel_transfer_no      = $data->channelTransferNo ?? $this->channel_transfer_no;
        $this->transfer_status          = TransferStatusEnum::FAIL;
        $this->extension->error_message = $data->message;
        $this->processing_time          = now();
        $this->fireModelEvent('fail', false);
    }


    public function isAllowClose() : bool
    {
        if ($this->transfer_status !== TransferStatusEnum::FAIL) {
            return false;
        }
        return true;
    }


    /**
     * @return void
     * @throws PaymentException
     */
    public function close() : void
    {
        if (!$this->isAllowClose()) {
            throw new PaymentException('状态错误');
        }
        $this->transfer_status = TransferStatusEnum::CLOSED;
        $this->fireModelEvent('close', false);

    }

    public function isAllowProcessing() : bool
    {
        if (!in_array($this->transfer_status,
            [
                TransferStatusEnum::FAIL,
                TransferStatusEnum::PENDING,
            ], true
        )) {
            return false;
        }
        return true;
    }

    /**
     * 处理中
     * @param ChannelTransferData $data
     * @return void
     * @throws PaymentException
     */
    public function processing(ChannelTransferData $data) : void
    {
        if (!$this->isAllowProcessing()) {
            throw new PaymentException('状态错误');
        }
        $this->channel_transfer_no = $data->channelTransferNo;
        $this->transfer_status     = TransferStatusEnum::PROCESSING;
        $this->processing_time     = now();
        $this->fireModelEvent('processing', false);
    }

    public function isAllowAbnormal() : bool
    {
        if (!in_array($this->transfer_status,
            [
                TransferStatusEnum::PENDING,
            ], true
        )) {
            return false;
        }
        return true;
    }

    // 异常 需要经过确认

    /**
     * @param string|null $message
     * @return void
     * @throws PaymentException
     */
    public function abnormal(?string $message = null) : void
    {

        if (!$this->isAllowAbnormal()) {
            throw new PaymentException('状态错误');
        }
        $this->transfer_status          = TransferStatusEnum::ABNORMAL;
        $this->processing_time          = now();
        $this->extension->error_message = $message;
        $this->fireModelEvent('abnormal', false);

    }

    public function isAllowRefund() : bool
    {
        if (!in_array($this->transfer_status,
            [
                TransferStatusEnum::PENDING,
                TransferStatusEnum::PROCESSING,
                TransferStatusEnum::FAIL,
                TransferStatusEnum::ABNORMAL,
            ], true
        )) {
            return false;
        }
        return true;
    }

    /**
     * @param ChannelTransferData $data
     * @return void
     * @throws PaymentException
     */
    public function refund(ChannelTransferData $data) : void
    {
        if (!$this->isAllowRefund()) {
            throw new PaymentException('状态错误');
        }
        $this->transfer_status = TransferStatusEnum::REFUND;
        // 二阶段已退款
        $this->fireModelEvent('refund', false);
    }
}
