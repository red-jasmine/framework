<?php

namespace RedJasmine\Payment\Domain\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedJasmine\Payment\Domain\Data\ChannelTransferData;
use RedJasmine\Payment\Domain\Events\Settles\SettleCreatedEvent;
use RedJasmine\Payment\Domain\Exceptions\SettleException;
use RedJasmine\Payment\Domain\Gateway\Data\ChannelSettleResult;
use RedJasmine\Payment\Domain\Generator\SettleNumberGeneratorInterface;
use RedJasmine\Payment\Domain\Models\Enums\SettleStatusEnum;
use RedJasmine\Payment\Domain\Models\Extensions\SettleDetail;
use RedJasmine\Support\Domain\Casts\MoneyOldCast;
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
            $settle->settle_status = SettleStatusEnum::PRE;
            $settle->details->each(function (SettleDetail $detail) use ($settle) {
                $detail->settle_no     = $settle->settle_no;
                $detail->settle_status = SettleStatusEnum::PRE;

            });
        });
    }


    protected $dispatchesEvents = [
        'created' => SettleCreatedEvent::class,
    ];

    public function getTable() : string
    {
        return 'payment_settles';
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
        $this->settle_no = app(SettleNumberGeneratorInterface::class)->generator(
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
            'amount'        => MoneyOldCast::class,
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

    public function setTrade(Trade $trade) : void
    {
        $this->trade_no              = $trade->trade_no;
        $this->merchant_id           = $trade->merchant_id;
        $this->merchant_app_id       = $trade->merchant_app_id;
        $this->channel_trade_no      = $trade->channel_trade_no;
        $this->system_channel_app_id = $trade->system_channel_app_id;
        $this->channel_app_id        = $trade->channel_app_id;
        $this->channel_code          = $trade->channel_code;
        $this->channel_merchant_id   = $trade->channel_merchant_id;
    }


    public function isAllowCancel() : bool
    {
        if ($this->settle_status !== SettleStatusEnum::PRE) {
            return false;
        }
        return true;
    }


    /**
     * @return void
     * @throws SettleException
     */
    public function cancel() : void
    {
        if (!$this->isAllowCancel()) {
            throw new SettleException('状态错误');
        }
        $this->settle_status  = SettleStatusEnum::CANCEL;
        $this->executing_time = now();
        $this->fireModelEvent('cancel', false);

    }


    public function isAllowSuccess() : bool
    {
        if (!in_array($this->settle_status,
            [
                SettleStatusEnum::FAIL,
                SettleStatusEnum::PROCESSING,
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
     * @throws SettleException
     */
    public function success(ChannelTransferData $data) : void
    {
        if (!$this->isAllowSuccess()) {
            throw new SettleException('状态不一致');
        }
        $this->channel_transfer_no = $data->channelTransferNo;
        $this->settle_status       = SettleStatusEnum::SUCCESS;
        $this->transfer_time       = $data->transferTime ?? now();

        $this->fireModelEvent('success', false);
    }

    public function isAllowFail() : bool
    {
        if (!in_array($this->settle_status,
            [
                SettleStatusEnum::FAIL,
                SettleStatusEnum::PENDING,
                SettleStatusEnum::PROCESSING,
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
     * @throws SettleException
     */
    public function fail(ChannelTransferData $data) : void
    {
        if (!$this->isAllowFail()) {
            throw new SettleException('状态不一致');
        }
        $this->channel_transfer_no      = $data->channelTransferNo ?? $this->channel_transfer_no;
        $this->settle_status            = SettleStatusEnum::FAIL;
        $this->extension->error_message = $data->message;
        $this->processing_time          = now();
        $this->fireModelEvent('fail', false);
    }


    public function isAllowClose() : bool
    {
        if ($this->settle_status !== SettleStatusEnum::FAIL) {
            return false;
        }
        return true;
    }


    /**
     * @return void
     * @throws SettleException
     */
    public function close() : void
    {
        if (!$this->isAllowClose()) {
            throw new SettleException('状态错误');
        }
        $this->settle_status = SettleStatusEnum::CLOSED;
        $this->fireModelEvent('close', false);

    }

    public function isAllowProcessing() : bool
    {
        if (!in_array($this->settle_status,
            [
                SettleStatusEnum::FAIL,
                SettleStatusEnum::PENDING,
            ], true
        )) {
            return false;
        }
        return true;
    }

    /**
     * 处理中
     * @param ChannelSettleResult $data
     * @return void
     * @throws SettleException
     */
    public function processing(ChannelSettleResult $data) : void
    {
        if (!$this->isAllowProcessing()) {
            throw new SettleException('状态错误');
        }
        $this->channel_settle_no = $data->channelSettleNo;
        $this->settle_status     = SettleStatusEnum::PROCESSING;
        $this->processing_time   = now();
        $this->fireModelEvent('processing', false);
    }

    public function isAllowAbnormal() : bool
    {
        if (!in_array($this->settle_status,
            [
                SettleStatusEnum::PENDING,
            ], true
        )) {
            return false;
        }
        return true;
    }

    /**
     * @param string|null $message
     * @return void
     * @throws SettleException
     */
    public function abnormal(?string $message = null) : void
    {

        if (!$this->isAllowAbnormal()) {
            throw new SettleException('状态错误');
        }
        $this->settle_status   = SettleStatusEnum::ABNORMAL;
        $this->processing_time = now();
        $this->fireModelEvent('abnormal', false);

    }


}
