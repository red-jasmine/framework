<?php

namespace RedJasmine\Payment\Domain\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use JsonException;
use RedJasmine\Payment\Domain\Models\ValueObjects\GoodDetail;

class GoodDetailCollectionCast implements CastsAttributes
{
    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string
     * @throws JsonException
     */
    public function set(Model $model, string $key, mixed $value, array $attributes)
    {

        return json_encode($value ?? [], JSON_THROW_ON_ERROR);
    }


    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return GoodDetail::collect(json_decode($value, true, 512, JSON_THROW_ON_ERROR) ?? []);
    }


}
