<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MoneyOldCast implements CastsAttributes, Cast, Transformer
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

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?MoneyOld
    {
        $key        = Str::snake($key);
        $moneyValue = $attributes[$this->getValueKey($key)] ?? 0;
        $currency   = $attributes[$this->getCurrencyKey($key)] ?? null;
        if (blank($currency)) {
            return null;
        }

        return new MoneyOld($moneyValue, $currency);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : ?array
    {
        $key = Str::snake($key);
        if (blank($value)) {
            return null;
        }
        if(is_string($value) || is_numeric($value)){
            $value = new MoneyOld($value);
        }
        return [
            $this->getValueKey($key)    => $value->value,
            $this->getCurrencyKey($key) => $value->currency,
        ];

    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : ?MoneyOld
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
        if($value instanceof MoneyOld){
            return $value;
        }
        return new MoneyOld($data['value'], $data['currency'] ?? MoneyOld::DEFAULT_CURRENCY);
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
