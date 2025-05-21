<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Money\Currency;
use Money\Money;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyCast implements CastsAttributes, Cast, Transformer
{

    protected ?string $valueKey    = null;
    protected ?string $currencyKey = null;

    protected string $valueSuffix    = 'amount';
    protected string $currencySuffix = 'currency';

    protected function getValueKey(string $key)
    {
        return $this->valueKey ?? $key.'_'.$this->valueSuffix;
    }

    protected function getCurrencyKey(string $key)
    {
        return $this->currencyKey ?? $key.'_'.$this->currencySuffix;
    }

    public function __construct(...$args)
    {

        $this->valueKey    = $args[0] ?? null;
        $this->currencyKey = $args[1] ?? null;

    }

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?Money
    {
        $key = Str::snake($key);

        $moneyValue = $attributes[$this->getValueKey($key)] ?? null;
        $currency   = $attributes[$this->getCurrencyKey($key)] ?? null;

        if (blank($currency) && blank($moneyValue)) {
            return null;
        }

        return new Money($moneyValue, new Currency($currency ?? 'CNY'));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : ?array
    {
        $key = Str::snake($key);
        if (blank($value)) {
            return [
                $this->getValueKey($key)    => null,
                $this->getCurrencyKey($key) => null,
            ];
        }

        if ($value instanceof Money) {
            return [
                $this->getValueKey($key)    => $value->getAmount(),
                $this->getCurrencyKey($key) => $value->getCurrency()->getCode(),
            ];
        }


        if (is_string($value) || is_numeric($value)) {
            $money = new Money($value, new Currency('CNY'));
        }

        return [
            $this->getValueKey($key)    => $money->getAmount(),
            $this->getCurrencyKey($key) => $money->getCurrency()->getCode(),
        ];

    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : ?Money
    {
        if (blank($value)) {
            return null;
        }
        $data = [];

        if (is_array($value)) {
            $data = $value;
        } elseif (is_string($value) || is_numeric($value)) {
            $data[$this->valueSuffix] = $value;
        }

        if (blank($data[$this->valueSuffix] ?? null) && blank($data[$this->currencySuffix] ?? null)) {
            return null;
        }
        if (blank($data[$this->valueSuffix] ?? null)) {
            return null;
        }

        if ($value instanceof Money) {
            return $value;
        }

        return new Money($data[$this->valueSuffix], new Currency($data[$this->currencySuffix] ?? 'CNY'));
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : ?array
    {
        if (blank($value)) {
            return null;
        }
        return [
            $this->currencySuffix => $value->getCurrency()->getCode(),
            $this->valueSuffix    => $value->amount,
        ];
    }


}
