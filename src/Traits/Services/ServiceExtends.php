<?php

namespace RedJasmine\Support\Traits\Services;

use Illuminate\Support\Traits\Macroable;

/**
 * @property $service
 */
trait ServiceExtends
{

    use Macroable {
        __call as macroCall;
    }

    /**
     * Magically call the JWT instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->service, $method)) {
            return call_user_func_array([ $this->service, $method ], $parameters);
        }

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        throw new BadMethodCallException("Method [$method] does not exist.");
    }
}
