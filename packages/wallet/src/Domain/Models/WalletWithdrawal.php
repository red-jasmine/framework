<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Contracts\OperatorInterface;
use RedJasmine\Support\Domain\Contracts\OwnerInterface;
use RedJasmine\Support\Domain\Contracts\UniqueNoInterface;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\Traits\HasApproval;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Wallet\Domain\Data\Payment\PaymentTransferData;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalCreatedEvent;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalFailEvent;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalSuccessEvent;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalTransferPrepareEvent;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Domain\Models\ValueObjects\Payee;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;


class WalletWithdrawal extends Model implements OwnerInterface, OperatorInterface, UniqueNoInterface
{

    use HasOwner;

    use HasOperator;


    use HasApproval;


    use HasDateTimeFormatter;

    use HasUniqueNo;

    use HasSnowflakeId;

    protected static string $uniqueNoPrefix = 'WW';

    protected static string $uniqueNoKey = 'withdrawal_no';

    public $incrementing = false;

    public function buildUniqueNoFactors() : array
    {
        return [
            $this->wallet_type,
            $this->owner_id,
        ];
    }

    protected $dispatchesEvents = [
        'created'         => WalletWithdrawalCreatedEvent::class,
        'success'         => WalletWithdrawalSuccessEvent::class,
        'fail'            => WalletWithdrawalFailEvent::class,
        'transferPrepare' => WalletWithdrawalTransferPrepareEvent::class,

    ];

    protected $observables = [
        'transferPrepare', 'fail', 'success'
    ];


    protected $fillable = [
        'wallet_id',
    ];

    protected function casts() : array
    {
        return [
            'status'                 => WithdrawalStatusEnum::class,
            'approval_status'        => ApprovalStatusEnum::class,
            'payment_status'         => PaymentStatusEnum::class,
            'amount'                 => MoneyCast::class.':currency,amount,true',// 支付金额
            'payment_amount'         => MoneyCast::class.':payment_currency,payment_amount,true',// 支付金额
            'payment_fee'            => MoneyCast::class.':payment_currency,payment_fee,true',// 支付金额
            'total_payment_amount'   => MoneyCast::class.':payment_currency,total_payment_amount,true',// 支付总金额
            'payment_channel_amount' => MoneyCast::class.':payment_currency,payment_channel_amount,true',// 支付渠道金额
        ];
    }


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }


    public function isAllowPaymentCallback() : bool
    {
        return ($this->payment_status === PaymentStatusEnum::PREPARE || $this->payment_status === PaymentStatusEnum::PAYING);

    }

    public function approvalPass(ApprovalData $data) : void
    {
        $this->transferPrepare();
    }

    public function approvalReject(ApprovalData $data) : void
    {
        $this->fail();
    }

    public function approvalRevoke(ApprovalData $data) : void
    {
        $this->fail();
    }

    /**
     * @param  PaymentTransferData  $data
     *
     * @return void
     * @throws WalletWithdrawalException
     */
    public function paymentCallback(PaymentTransferData $data) : void
    {
        if (!$this->isAllowPaymentCallback()) {
            throw new WalletWithdrawalException('当前状态不支持处理');
        }
        $this->payment_status           = $data->paymentStatus;
        $this->payment_time             = $data->paymentTime ?? Carbon::now();
        $this->payment_type             = $data->paymentType ?? $this->payment_type;
        $this->payment_id               = $data->paymentId ?? $this->payment_id;
        $this->payment_channel_trade_no = $data->paymentChannelTradeNo ?? $this->payment_channel_trade_no;

    }


    public function fail() : void
    {
        $this->status = WithdrawalStatusEnum::FAIL;

        $this->fireModelEvent('fail', false);
    }

    public function success() : void
    {
        $this->status = WithdrawalStatusEnum::SUCCESS;
        $this->fireModelEvent('success', false);
    }


    /**
     * 准备转账
     * @return void
     */
    public function transferPrepare() : void
    {
        $this->payment_status = PaymentStatusEnum::PREPARE;

        $this->fireModelEvent('transferPrepare', false);
    }

    public function canTransferPrepare() : bool
    {
        return $this->payment_status === PaymentStatusEnum::PREPARE;
    }

    protected function payee() : Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => Payee::from([
                'channel'      => $attributes['payee_channel'],
                'account_type' => $attributes['payee_account_type'],
                'account_no'   => $attributes['payee_account_no'],
                'name'         => $attributes['payee_name'],
                'certType'     => $attributes['payee_cert_type'],
                'certNo'       => $attributes['payee_cert_no'],

            ]),

        );
    }
}
