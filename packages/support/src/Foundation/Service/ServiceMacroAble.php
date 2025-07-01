<?php

namespace RedJasmine\Support\Foundation\Service;

use BadMethodCallException;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Traits\Macroable;
use ReflectionException;

/**
 * @method array  getMacros()
 * @method mixed  makeMacro($macro, $method, $parameters)
 * @method mixed  callMacro($macro, $method, $parameters)
 */
trait ServiceMacroAble
{

    use Macroable {
        Macroable::__call as macroCall;
        Macroable::hasMacro as macroHasMacro;
    }

    public static function hasMacro($name) : bool
    {
        // 怕断是否有 getMacris 静态方法
        if (method_exists(static::class, 'getMacros')) {
            return isset(static::getMacros()[$name]);
        }
        return isset(static::$macros[$name]);
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
        if (method_exists(static::class, 'getMacros')) {
            $macro = static::getMacros()[$method];
        } else {
            $macro = static::$macros[$method];
        }



        if ($macro instanceof Closure) {
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
