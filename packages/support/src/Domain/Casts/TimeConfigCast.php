<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Data\TimeConfigData;

class TimeConfigCast implements CastsAttributes
{

    protected function getUnitKey($key) : string
    {
        return $key.'_unit';
    }


    protected function getValueKey($key) : string
    {
        return $key.'_value';
    }

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?TimeConfigData
    {
        $key = Str::snake($key);

        $unit  = $attributes[$this->getUnitKey($key)] ?? null;
        $value = $attributes[$this->getValueKey($key)] ?? null;
        if (blank($unit) && blank($value)) {
            return null;
        }
        return TimeConfigData::from([
            'unit'  => $unit,
            'value' => $value,
        ]);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : array
    {
        $key = Str::snake($key);
        if (blank($value)) {
            return [
                $this->getUnitKey($key)  => null,
                $this->getValueKey($key) => null,
            ];
        }
        if (is_array($value)) {
            $value = TimeConfigData::from($value);
        }
        if ($value instanceof TimeConfigData) {
            return [
                $this->getUnitKey($key)  => $value->unit,
                $this->getValueKey($key) => $value->value,
            ];
        }
        return [
            $this->getUnitKey($key)  => null,
            $this->getValueKey($key) => null,
        ];

    }
}
