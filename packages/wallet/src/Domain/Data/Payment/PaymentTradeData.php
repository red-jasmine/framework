<?php

namespace RedJasmine\Wallet\Domain\Data\Payment;

use Illuminate\Support\Carbon;
use RedJasmine\Support\Foundation\Data\Data;
use RedJasmine\Wallet\Domain\Models\Enums\PaymentStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class PaymentTradeData extends Data
{


    public PaymentStatusEnum $paymentStatus;

    public ?string $paymentType = null;

    public ?string $paymentId = null;

    public ?string $paymentChannelTradeNo = null;


    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $paymentTime = null;

    /**
     * @var array
     */
    public array $context = [];

}