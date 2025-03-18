<?php

namespace RedJasmine\Wallet\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Traits\HasDateTimeFormatter;
use RedJasmine\Wallet\Domain\Models\Enums\AmountDirectionEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionStatusEnum;
use RedJasmine\Wallet\Domain\Models\Enums\TransactionTypeEnum;

class WalletTransaction extends Model
{

    use HasDateTimeFormatter;

    public $incrementing = false;

    protected $casts = [
        'status'           => TransactionStatusEnum::class,
        'direction'        => AmountDirectionEnum::class,
        'transaction_type' => TransactionTypeEnum::class
    ];

}
