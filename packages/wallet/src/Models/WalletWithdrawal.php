<?php

namespace RedJasmine\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Support\Traits\Models\HasOperator;
use RedJasmine\Support\Traits\Models\HasOwner;
use RedJasmine\Support\Traits\Models\WithDTO;
use RedJasmine\Wallet\Enums\Withdrawals\WithdrawalStatusEnum;

class WalletWithdrawal extends Model
{

    use WithDTO;

    use HasOwner;

    use HasDateTimeFormatter;

    use HasOperator;

    public $incrementing = false;

    protected $casts = [
        'status'                     => WithdrawalStatusEnum::class,
        'transfer_account_real_name' => 'encrypted'
    ];


    public function wallet() : BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id');
    }
}
