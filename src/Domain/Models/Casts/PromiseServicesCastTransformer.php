<?php

namespace RedJasmine\Ecommerce\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class PromiseServicesCastTransformer implements CastsAttributes, Cast, Transformer
{

    public function get(Model $model, string $key, mixed $value, array $attributes) : PromiseServices
    {
        return PromiseServices::from($this->decode($value));
    }

    /**
     * @param Model           $model
     * @param string          $key
     * @param PromiseServices $value
     * @param array           $attributes
     *
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes) : string
    {
        return $this->encode($value?->toArray() ?? []);
    }


    protected function encode(array $map) : string
    {
        return implode(";", array_map(function ($key, $value) {
            return $key . ":" . $value;
        }, array_keys($map), array_values($map)));
    }

    protected function decode(string $string) : array
    {
        if (filled($string)) {
            $array = [];
            $parts = explode(';', $string);

            foreach ($parts as $part) {
                $keyValue            = explode(':', $part);
                $array[$keyValue[0]] = $keyValue[1];
            }
            return $array;
        }
        return [];
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : mixed
    {
        return PromiseServices::from($value);
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : mixed
    {
        return $value->toArray();
    }


}
