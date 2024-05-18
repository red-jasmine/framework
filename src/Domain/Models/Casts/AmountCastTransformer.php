<?php

namespace RedJasmine\Ecommerce\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Ecommerce\Domain\Models\ValueObjects\Amount;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class AmountCastTransformer implements CastsAttributes, Cast, Transformer
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context) : Amount
    {
        return new Amount($value);
    }


    /**
     * @param DataProperty          $property
     * @param Amount                $value
     * @param TransformationContext $context
     *
     * @return mixed
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context) : string
    {
        return (string)$value;
    }


    public function get(Model $model, string $key, mixed $value, array $attributes) : Amount
    {
        return new Amount($value);
    }

    /**
     *
     * @param Model  $model
     * @param string $key
     * @param Amount $value
     * @param array  $attributes
     *
     * @return string
     */
    public function set(Model $model, string $key, mixed $value, array $attributes) : string
    {
        if ($value instanceof Amount) {
            return $value->value();
        }
        return (string)$value;
    }


}
