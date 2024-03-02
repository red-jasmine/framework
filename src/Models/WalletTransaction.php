<?php

namespace RedJasmine\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Wallet\Enums\AmountDirection;
use RedJasmine\Wallet\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Enums\TransactionTypeEnum;

class WalletTransaction extends Model
{

    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $casts = [
        'status'           => TransactionStatusEnum::class,
        'direction'        => AmountDirection::class,
        'transaction_type' => TransactionTypeEnum::class
    ];

}
