<?php

namespace RedJasmine\Support\Traits\Models;


/**
 * @mixin  Illuminate\Database\Eloquent\Model
 * @mixin  Illuminate\Database\Eloquent\Model
 */
trait ParametersMakeAble
{

    protected ?array $parameters = null;

    public function getParameters() : array
    {
        return $this->parameters ?? $this->toArray();
    }

    public function getParameter($key)
    {
        if (!$key) {
            return;
        }
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }
    }

    public function setParameters(array $parameters) : static
    {
        $this->parameters = $parameters;
        $this->fill($parameters);
        return $this;
    }

    public static function makeParameters(array $parameters) : static
    {
        $model             = static::make($parameters);
        $model->parameters = $parameters;
        return $model;
    }

}
