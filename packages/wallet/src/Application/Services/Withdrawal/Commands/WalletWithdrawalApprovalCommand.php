<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Data\ApprovalData;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class WalletWithdrawalApprovalCommand extends ApprovalData
{


    protected string $primaryKey = 'withdrawal_no';


}