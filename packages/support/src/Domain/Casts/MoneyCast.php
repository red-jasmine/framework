<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes) : ?Money
    {
        $key        = Str::snake($key);
        $moneyValue = $attributes[$key . '_value'] ?? 0;
        $currency   = $attributes[$key . '_currency'] ?? null;
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
                $key . '_value'    => 0,
                $key . '_currency' => null,
            ];
        }
        return [
            $key . '_value'    => $value->value,
            $key . '_currency' => $value->currency,
        ];
    }


}
