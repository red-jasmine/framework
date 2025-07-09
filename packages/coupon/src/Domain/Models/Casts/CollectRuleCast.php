<?php

namespace RedJasmine\Coupon\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Coupon\Domain\Models\ValueObjects\CollectRule;

class CollectRuleCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?CollectRule
    {
        if (is_null($value)) {
            return null;
        }

        $data = is_string($value) ? json_decode($value, true) : $value;

        if (!is_array($data)) {
            return null;
        }

        return new CollectRule($data);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof CollectRule) {
            return json_encode($value->items->toArray());
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
} 