<?php

namespace RedJasmine\Wallet\Application\Services\Withdrawal\Commands;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\ApprovalStatusEnum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class WalletWithdrawalApprovalCommand extends Data
{
    public string $withdrawalNo;

    #[WithCast(EnumCast::class, ApprovalStatusEnum::class)]
    public ApprovalStatusEnum $status;


    #[Max(200)]
    public ?string $message = null;


}