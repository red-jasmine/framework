<?php

namespace RedJasmine\Support\Domain\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Data\TimeConfigData;

class TimeConfigCast implements CastsAttributes
{
    protected function getTypeKey($key) : string
    {
        return $key.'_type';
    }

    protected function getValueKey($key) : string
    {
        return $key.'_value';
    }

    public function get(Model $model, string $key, mixed $value, array $attributes) : ?TimeConfigData
    {
        $key = Str::snake($key);

        $type  = $attributes[$this->getTypeKey($key)] ?? null;
        $value = $attributes[$this->getValueKey($key)] ?? null;
        if (blank($type) && blank($value)) {
            return null;
        }
        return TimeConfigData::from([
            'type'  => $type,
            'value' => $value,
        ]);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes) : array
    {
        $key = Str::snake($key);
        if (blank($value)) {
            return [
                $this->getTypeKey($key)  => null,
                $this->getValueKey($key) => null,
            ];
        }
        if (is_array($value)) {
            $value = TimeConfigData::from($value);
        }
        if ($value instanceof TimeConfigData) {
            return [
                $this->getTypeKey($key)  => $value->type,
                $this->getValueKey($key) => $value->value,
            ];
        }
        return [
            $this->getTypeKey($key)  => null,
            $this->getValueKey($key) => null,
        ];

    }
}
