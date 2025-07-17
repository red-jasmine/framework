<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;
use RedJasmine\Wallet\Domain\Data\Recharge\RechargePaymentData;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Recharges\RechargeStatusEnum;
use RedJasmine\Wallet\Exceptions\WalletRechargeException;

class WalletRecharge extends Model implements OwnerInterface, OperatorInterface, UniqueNoInterface
{

    protected static string $uniqueNoKey = 'recharge_no';
    use HasUniqueNo;
    public function buildUniqueNoFactors() : array
    {
        return [
            $this->wallet_type,
            $this->owner_type,
            $this->owner_id,
        ];
    }


    use HasOwner;
    use HasSnowflakeId;
    use HasDateTimeFormatter;
    use HasOperator;

    public $incrementing = false;

    protected $casts = [
        'status'                 => RechargeStatusEnum::class,
        'extra'                  => 'array',
        'amount'                 => MoneyCast::class.':currency,amount',// 充值金额
        'payment_status'         => PaymentStatusEnum::class,
        'payment_amount'         => MoneyCast::class.':payment_currency,payment_amount,true',// 支付金额
        'payment_fee'            => MoneyCast::class.':payment_currency,payment_fee,true',// 支付金额
        'total_payment_amount'   => MoneyCast::class.':payment_currency,total_payment_amount,true',// 支付总金额
        'payment_channel_amount' => MoneyCast::class.':payment_currency,payment_channel_amount,true',// 支付渠道金额
    ];


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }


    public function canPaid() : bool
    {
        if ($this->payment_status === PaymentStatusEnum::SUCCESS) {
            return false;
        }
        if ($this->status === RechargeStatusEnum::PAID) {
            return false;
        }
        if ($this->status === RechargeStatusEnum::SUCCESS) {
            return false;
        }

        return true;
    }


    public function success() : void
    {

        $this->status        = RechargeStatusEnum::SUCCESS;
        $this->recharge_time = Carbon::now();

    }

    /**
     * @param  RechargePaymentData  $data
     *
     * @return void
     * @throws WalletRechargeException
     */
    public function paid(RechargePaymentData $data) : void
    {
        if (!$this->canPaid()) {
            throw new WalletRechargeException('充值单无法支付');
        }
        $this->status                   = RechargeStatusEnum::PAID;
        $this->payment_status           = PaymentStatusEnum::SUCCESS;
        $this->payment_type             = $data->paymentType;
        $this->payment_id               = $data->paymentId;
        $this->payment_time             = $data->paymentTime ?? Carbon::now();
        $this->payment_channel_trade_no = $data->paymentChannelTradeNo;
        $this->payment_mode             = $data->paymentMode;
        $this->payment_channel_amount   = $data->paymentAmount;

    }


}
