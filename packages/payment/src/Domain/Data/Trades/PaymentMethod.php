<?php

namespace RedJasmine\Payment\Domain\Data\Trades;

use RedJasmine\Support\Foundation\Data\Data;

class PaymentMethod extends Data
{
    public string $code;

    public string $name;

    public ?string $icon = null;

}