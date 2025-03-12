<?php

namespace RedJasmine\Payment\Application\Services\Payer\Commands;

use RedJasmine\Payment\Domain\Data\PaymentEnvironmentData;
use Spatie\LaravelData\Attributes\Validation\Required;

class PayerLoginCommand extends PaymentEnvironmentData
{
    public string $merchantAppId;

    #[Required]
    public string $channelAppId;
    #[Required]
    public string $code;
}