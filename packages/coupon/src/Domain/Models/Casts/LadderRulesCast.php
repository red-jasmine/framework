<?php

namespace RedJasmine\Coupon\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use RedJasmine\Coupon\Domain\Models\ValueObjects\LadderRule;

class LadderRulesCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Collection
    {
        if (is_null($value)) {
            return collect();
        }

        $data = is_string($value) ? json_decode($value, true) : $value;

        if (!is_array($data)) {
            return collect();
        }

        return collect($data)->map(function ($item) {
            return new LadderRule($item);
        });
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
} 