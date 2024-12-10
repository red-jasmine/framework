<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use RedJasmine\Support\Data\Data;

class Money extends Data
{

    public string $currency;


    public int $amount;

}
