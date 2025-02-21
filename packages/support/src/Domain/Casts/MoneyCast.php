<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyCast implements CastsAttributes, Cast, Transformer
{

    protected ?string $valueKey    = null;
    protected ?string $currencyKey = null;

    protected function getValueKey(string $key)
    {
        return $this->valueKey ?? $key.'_value';
    }

    protected function getCurrencyKey(string $key)
    {
        return $this->currencyKey ?? $key.'_currency';
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

    public function set(Model $model, string $key, mixed $value, array $attributes) : ?array
    {
        $key = Str::snake($key);
        if (blank($value)) {
            return null;
        }
        if(is_string($value) || is_numeric($value)){
            $value = new Money($value);
        }
        return [
            $this->getValueKey($key)    => $value->value,
            $this->getCurrencyKey($key) => $value->currency,
        ];

    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : ?Money
    {
        if (blank($value)) {
            return null;
        }
        $data = [];
        if(is_array($value)){
            $data = $value;
        }elseif (is_string($value) || is_numeric($value)){
            $data['value'] = $value;
        }
        if($value instanceof Money){
            return $value;
        }
        return new Money($data['value'], $data['currency'] ?? Money::DEFAULT_CURRENCY);
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : ?array
    {
        if (blank($value)) {
            return null;
        }
        return [
            'currency' => $value->currency,
            'value'    => $value->value,
        ];
    }


}
