<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\ValueObjects\Amount;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

/**
 * @deprecated
 */
class AmountCast implements CastsAttributes, Cast, Transformer
{

    protected ?string $valueKey    = null;
    protected ?string $currencyKey = null;

    protected string $valueSuffix    = 'total';
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

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?Amount
    {
        $key        = Str::snake($key);
        $moneyValue = $attributes[$this->getValueKey($key)] ?? null;
        $currency   = $attributes[$this->getCurrencyKey($key)] ?? null;
        if (blank($currency) && blank($moneyValue)) {
            return null;
        }

        return new Amount($moneyValue, $currency);
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
        if (is_string($value) || is_numeric($value)) {
            $value = new Amount($value);
        }
        return [
            $this->getValueKey($key)    => $value->total,
            $this->getCurrencyKey($key) => $value->currency,
        ];

    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : ?Amount
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

        if ($value instanceof Amount) {
            return $value;
        }
        return new Amount($data[$this->valueSuffix], $data[$this->currencySuffix] ?? Amount::DEFAULT_CURRENCY);
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : ?array
    {
        if (blank($value)) {
            return null;
        }
        return [
            $this->currencySuffix => $value->currency,
            $this->valueSuffix    => $value->total,
        ];
    }


}
