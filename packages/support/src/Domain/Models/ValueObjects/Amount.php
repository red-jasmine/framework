<?php

namespace RedJasmine\Support\Domain\Models\ValueObjects;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use RedJasmine\Support\Data\Data;

class Amount extends Data
{

    public const string DEFAULT_CURRENCY = 'CNY';

    public function __construct(
        public float|int $total = 0,
        public string $currency = self::DEFAULT_CURRENCY
    ) {
    }

    public static function make(string|int|float $total = 0, string $currency = self::DEFAULT_CURRENCY) : Amount
    {

        return new static((float) (string) $total, $currency ?? self::DEFAULT_CURRENCY);
    }


    public function format() : string
    {

        $money          = new \Money\Money(bcmul($this->total, 100, 0), new Currency($this->currency));
        $currencies     = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);
        return $moneyFormatter->format($money);
    }


    public function equal(self $money) : bool
    {
        if ($this->total === $money->total
            && $money->currency === $this->currency
        ) {
            return true;
        }

        return false;

    }

    public function compare(self $money) : int
    {
        return bccomp($this->total, $money->total, 0);
    }

    public function add(self $money) : static
    {
        $that =  clone $this;
        $that->total = bcadd($money->total, $this->total, 0);
        return $this;
    }

    public function sub(self $money) : static
    {
        $this->total = bcsub($this->total, $money->total, 0);
        return $this;
    }

    public function mul($value) : static
    {
        $this->total = bcmul($this->total, $value, 0);
        return $this;
    }

    public function abs() : static
    {
        // 取绝对值
        if (bccomp($this->total, 0, 2) < 0) {
            $this->total = bcmul($this->total, -1, 2);
        }
        return $this;
    }


    public function total() : string
    {
        return $this->total;
    }


    public function __toString() : string
    {
        return $this->total;
    }

}
