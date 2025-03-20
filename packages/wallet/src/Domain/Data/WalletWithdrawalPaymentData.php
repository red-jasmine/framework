<?php

namespace RedJasmine\Wallet\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Data\Data;
use RedJasmine\Wallet\Domain\Models\Enums\Withdrawals\WithdrawalPaymentStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class WalletWithdrawalPaymentData extends Data
{

    public WithdrawalPaymentStatusEnum $paymentStatus;

    public ?string $paymentType = null;

    public ?string $paymentId = null;

    public ?string $paymentChannelTradeNo = null;


    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $paymentTime = null;

}