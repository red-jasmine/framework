<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class CurrencyCast implements CastsAttributes, Cast, Transformer
{
    public function get(Model $model, string $key, mixed $value, array $attributes) : Currency
    {

        if (blank($value)) {
            return $value;
        }
        if ($value instanceof Currency) {
            return $value;
        }

        return new Currency($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : array
    {
        if (blank($value)) {
            return [
                $key => null,
            ];
        }
        return [
            $key => $value instanceof Currency ? $value->getCode() : (string) $value,
        ];

    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : ?Currency
    {
        if (blank($value)) {
            return null;
        }
        if ($value instanceof Currency) {
            return $value;
        }

        return new Currency($value);
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : ?string
    {
        if (blank($value)) {
            return null;
        }
        return $value instanceof Currency ? $value->getCode() : (string) $value;
    }


}