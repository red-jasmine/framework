<?php

namespace RedJasmine\Product\Domain\Product\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class SalePropsCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        // TODO: Implement get() method.
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        dd($value);
    }


}
