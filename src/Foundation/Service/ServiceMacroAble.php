<?php

namespace RedJasmine\Support\Foundation\Service;

use BadMethodCallException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Traits\Macroable;
use ReflectionException;


trait ServiceMacroAble
{

    use Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function __call($method, $parameters)
    {
        if (!static::hasMacro($method)) {

            throw new BadMethodCallException(sprintf(
                                                 'Method %s::%s does not exist.', static::class, $method
                                             ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof \Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        if (method_exists($this, 'makeMacro')) {
            $macro = $this->makeMacro($macro, $method, $parameters);
        }
        if (method_exists($this, 'callMacro')) {

            return $this->callMacro($macro, $method, $parameters);
        }

        return $macro(...$parameters);
    }


}
