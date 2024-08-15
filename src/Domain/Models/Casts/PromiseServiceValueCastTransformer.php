<?php

namespace RedJasmine\Order\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\PromiseServiceValue;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class PromiseServiceValueCastTransformer implements Cast, Transformer, CastsAttributes
{

    public function get(Model $model, string $key, mixed $value, array $attributes) : PromiseServiceValue
    {
        return new PromiseServiceValue($value);
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : PromiseServiceValue
    {
        return new PromiseServiceValue($value);
    }

    /**
     * @param Model               $model
     * @param string              $key
     * @param PromiseServiceValue $value
     * @param array               $attributes
     *
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes) : string
    {
        return $value->value();
    }


    /**
     * @param DataProperty          $property
     * @param PromiseServiceValue   $value
     * @param TransformationContext $context
     *
     * @return mixed
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : string
    {
        return $value->value();
    }


}
