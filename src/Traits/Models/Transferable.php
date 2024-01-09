<?php

namespace RedJasmine\Support\Traits\Models;


/**
 * @mixin  Illuminate\Database\Eloquent\Model
 */
trait Transferable
{

    protected ?array $parameters = null;

    public function getParameters() : array
    {
        return $this->parameters ?? $this->toArray();
    }

    public function setParameters(array $parameters) : static
    {
        $this->parameters = $parameters;
        $this->fill($parameters);
        return $this;
    }

    public static function transferFrom(array $parameters)
    {
        $model             = static::make($parameters);
        $model->parameters = $parameters;
        return $model;
    }

    public static function makeParameters(array $parameters) : static
    {
        $model             = static::make($parameters);
        $model->parameters = $parameters;
        return $model;
    }

}
