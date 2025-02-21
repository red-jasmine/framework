<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

class MoneyCast implements CastsAttributes
{

    protected ?string $valueKey    = null;
    protected ?string $currencyKey = null;

    protected function getValueKey(string $key)
    {
        return $this->valueKey ?? $key.'_value';
    }

    protected function getCurrencyKey(string $key)
    {
        return $this->currencyKey ?? $key.'_value';
    }

    public function __construct(...$args)
    {

        $this->valueKey    = $args[0] ?? null;
        $this->currencyKey = $args[1] ?? null;

    }

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?Money
    {
        $key        = Str::snake($key);
        $moneyValue = $attributes[$this->getValueKey($key)] ?? 0;
        $currency   = $attributes[$this->getCurrencyKey($key)] ?? null;
        if (blank($currency)) {
            return null;
        }

        return new Money($moneyValue, $currency);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : array
    {
        $key = Str::snake($key);
        if (blank($value)) {
            return [
                $this->getValueKey($key)    => 0,
                $this->getCurrencyKey($key) => null,
            ];
        }
        return [
            $this->getValueKey($key)    => $value->value,
            $this->getCurrencyKey($key) => $value->currency,
        ];
    }


}
