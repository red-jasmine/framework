<?php

namespace RedJasmine\Support\Domain\Models\ValueObjects;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use RedJasmine\Support\Data\Data;

class Money extends Data
{


    public function __construct(
        public int $value = 0,
        public string $currency = 'CNY',
    ) {
    }

    public static function make(int $value = 0, string $currency = 'CNY') : Money
    {
        return new static($value, $currency);
    }


    public function format() : string
    {
        $money          = new \Money\Money($this->value, new Currency($this->currency));
        $currencies     = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        return $moneyFormatter->format($money);
    }


    public function equal(self $money) : bool
    {
        if ($this->value === $money->value
            && $money->currency === $this->currency
        ) {
            return true;
        }

        return false;

    }

    public function compare(self $money) : int
    {
        return bccomp($this->value, $money->value, 0);
    }

    public function add(self $money) : static
    {
        $this->value = bcadd($money->value, $this->value, 0);
        return $this;
    }

    public function sub(self $money) : static
    {
        $this->value = bcsub($this->value, $money->value, 0);
        return $this;
    }

    public function mul($value) : static
    {
        $this->value = bcmul($this->value, $value, 0);
        return $this;
    }

}
