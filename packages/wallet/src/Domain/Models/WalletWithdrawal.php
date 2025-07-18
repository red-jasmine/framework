<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use RedJasmine\Support\Domain\Casts\MoneyCast;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\UniqueNoInterface;
use RedJasmine\Wallet\Domain\Data\WalletWithdrawalPaymentData;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalCreatedEvent;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalFailEvent;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalPaymentPrepareEvent;
use RedJasmine\Wallet\Domain\Events\WalletWithdrawal\WalletWithdrawalSuccessEvent;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalPaymentStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;
use RedJasmine\Wallet\Exceptions\WalletWithdrawalException;


class WalletWithdrawal extends Model implements OwnerInterface, OperatorInterface, UniqueNoInterface
{

    use HasOwner;

    use HasOperator;


    use HasDateTimeFormatter;

    use HasUniqueNo;

    protected static string $uniqueNoPrefix = 'WW';

    public $incrementing = false;


    protected $dispatchesEvents = [
        'created'        => WalletWithdrawalCreatedEvent::class,
        'success'        => WalletWithdrawalSuccessEvent::class,
        'fail'           => WalletWithdrawalFailEvent::class,
        'paymentPrepare' => WalletWithdrawalPaymentPrepareEvent::class
    ];

    protected $observables = [
        'paymentPrepare', 'fail', 'success'
    ];

    use HasSnowflakeId;

    protected $fillable = [
        'wallet_id',
    ];
    protected $casts    = [
        'status'           => WithdrawalStatusEnum::class,
        'approval_status'  => ApprovalStatusEnum::class,
        'payment_status'   => WithdrawalPaymentStatusEnum::class,
        'amount'           => MoneyCast::class, ':amount_currency,amount_total',
        'payee_name'       => 'encrypted',
        'payee_account_no' => 'encrypted',
        'payee_cert_no'    => 'encrypted',
        'extra'            => 'array',
    ];


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }

    public function isAllowApproval() : bool
    {
        return $this->approval_status === ApprovalStatusEnum::PENDING;

    }

    /**
     * @param  ApprovalStatusEnum  $approvalStatus
     * @param  string|null  $approvalMessage
     *
     * @return void
     * @throws WalletWithdrawalException
     */
    public function approval(ApprovalStatusEnum $approvalStatus, ?string $approvalMessage = null) : void
    {
        if (!$this->isAllowApproval()) {
            throw new WalletWithdrawalException('当前状态不允许审批');
        }
        $this->approval_status  = $approvalStatus;
        $this->approval_time    = Carbon::now();
        $this->approval_message = $approvalMessage;

        $this->fireModelEvent('approval', false);

    }


    public function isAllowPaymentCallback() : bool
    {
        return ($this->payment_status === WithdrawalPaymentStatusEnum::PREPARE || $this->payment_status === WithdrawalPaymentStatusEnum::PAYING);

    }

    /**
     * @param  WalletWithdrawalPaymentData  $data
     *
     * @return void
     * @throws WalletWithdrawalException
     */
    public function paymentCallback(WalletWithdrawalPaymentData $data) : void
    {
        if (!$this->isAllowPaymentCallback()) {
            throw new WalletWithdrawalException('当前状态不支持处理');
        }
        $this->payment_status = $data->paymentStatus;
        $this->payment_time   = $data->paymentTime ?? Carbon::now();

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
     * 准备支付
     * @return void
     */
    public function paymentPrepare() : void
    {
        $this->payment_status = WithdrawalPaymentStatusEnum::PREPARE;

        $this->fireModelEvent('paymentPrepare', false);
    }
}
