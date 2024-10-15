<?php

namespace RedJasmine\Ecommerce\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use JsonException;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServices;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class PromiseServicesCastTransformer implements CastsAttributes, Cast, Transformer
{

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     *
     * @return PromiseServices
     * @throws JsonException
     */
    public function get(Model $model, string $key, mixed $value, array $attributes) : PromiseServices
    {
        return PromiseServices::from(json_decode($value, false, 512, JSON_THROW_ON_ERROR));
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  PromiseServices  $value
     * @param  array  $attributes
     *
     * @return string
     * @throws JsonException
     */
    public function set(Model $model, string $key, mixed $value, array $attributes) : string
    {

        return json_encode($value?->toArray() ?? [], JSON_THROW_ON_ERROR);
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
