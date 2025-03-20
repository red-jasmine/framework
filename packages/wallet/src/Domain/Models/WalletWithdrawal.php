<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Domain\Casts\AmountCast;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use RedJasmine\Support\Domain\Models\OperatorInterface;
use RedJasmine\Support\Domain\Models\OwnerInterface;
use RedJasmine\Support\Domain\Models\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Domain\Models\Traits\HasUniqueNo;
use RedJasmine\Support\Domain\Models\Traits\HasOperator;
use RedJasmine\Support\Domain\Models\Traits\HasOwner;
use RedJasmine\Support\Domain\Models\Traits\HasSnowflakeId;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalStatusEnum;

class WalletWithdrawal extends Model implements OwnerInterface, OperatorInterface
{

    use HasOwner;

    use HasOperator;


    use HasDateTimeFormatter;


    public $uniqueNoKey = 'withdrawal_no';

    use HasUniqueNo;

    public $incrementing = false;

    use HasSnowflakeId;

    protected $fillable = [
        'wallet_id',
    ];
    protected $casts    = [
        'status'           => WithdrawalStatusEnum::class,
        'approval_status'  => ApprovalStatusEnum::class,
        'amount'           => AmountCast::class,
        'payee_name'       => 'encrypted',
        'payee_account_no' => 'encrypted',
        'payee_cert_no'    => 'encrypted',
    ];

    public function newUniqueNo() : string
    {
        return implode('', [
            $this->generateDatetimeId(),
            $this->factorRemainder($this->owner_type),
            $this->factorRemainder($this->owner_id),
            $this->factorRemainder($this->wallet_id),
        ]);
    }


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
