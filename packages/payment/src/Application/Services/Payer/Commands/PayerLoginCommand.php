<?php

namespace RedJasmine\Payment\Application\Services\Payer\Commands;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\Validation\Required;

class PayerLoginCommand extends Data
{
    public string $merchantAppId;

    //
    public string $channelAppId;
    #[Required]
    public string $code;
}