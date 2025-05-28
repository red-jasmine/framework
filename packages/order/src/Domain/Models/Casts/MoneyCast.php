<?php

namespace RedJasmine\Order\Domain\Models\Casts;

use Cknow\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Money\Currency;

class MoneyCast implements CastsAttributes
{
    protected string $valueSuffix    = 'amount';
    protected string $currencySuffix = 'currency';

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?Money
    {
        $key = Str::snake($key);

        $moneyValue = $attributes[$key] ?? null;
        $currency   = $attributes['currency'] ?? null;

        if (blank($moneyValue)) {
            return null;
        }

        return Money::parseByDecimal($moneyValue, $currency);

    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : ?array
    {
        $money = $value;
        $key   = Str::snake($key);
        if (blank($money)) {
            return [
                $key => null,
            ];
        }


        if (is_array($value)) {
            $money = new Money($value[$this->valueSuffix], new Currency($value[$this->currencySuffix]));
        }
        if (is_string($value) || is_numeric($value)) {
            $money = new Money($value, $attributes['currency']);
        }
        return [
            $key => $money->formatByDecimal(),
        ];

    }


}