<?php

namespace RedJasmine\Payment\Domain\Models\ValueObjects;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use RedJasmine\Support\Data\Data;

class Money extends Data
{


    public function __construct(
        public int    $value,
        public string $currency,
    )
    {
    }


    public function format() : string
    {
        $money          = new \Money\Money($this->value, new Currency($this->currency));
        $currencies     = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money);
    }
}
