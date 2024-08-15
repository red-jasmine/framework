<?php

namespace RedJasmine\Order\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;

class PromiseServicesCastTransformer implements CastsAttributes
{

    public function get(Model $model, string $key, mixed $value, array $attributes) : PromiseServices
    {
        return PromiseServices::from($this->decode($value));
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  PromiseServices  $value
     * @param  array  $attributes
     *
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes) : string
    {
        return $this->encode($value->toArray());
    }


    protected function encode(array $map) : string
    {
        return implode(";", array_map(function ($key, $value) {
            return $key.":".$value;
        }, array_keys($map), array_values($map)));
    }

    protected function decode(string $string) : array
    {
        $array = [];
        $parts = explode(';', $string);

        foreach ($parts as $part) {
            $keyValue            = explode(':', $part);
            $array[$keyValue[0]] = $keyValue[1];
        }
        return $array;
    }


}
