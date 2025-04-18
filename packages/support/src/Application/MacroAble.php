<?php

namespace RedJasmine\Support\Application;

use RedJasmine\Support\Foundation\Service\BadMethodCallException;
use RedJasmine\Support\Foundation\Service\Closure;
use RedJasmine\Support\Foundation\Service\ReflectionMethod;

trait MacroAble
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    protected static array $macros = [];

    /**
     * Register a custom macro.
     *
     * @param  string  $name
     * @param  callable|object  $macro
     *
     * @param-closure-this static  $macro
     *
     * @return void
     */
    public static function macro(string $name, callable|object $macro) : void
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Mix another object into the class.
     *
     * @param  object  $mixin
     * @param  bool  $replace
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public static function mixin(object $mixin, $replace = true)
    {
        $methods = (new \ReflectionClass($mixin))->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            if ($replace || !static::hasMacro($method->name)) {
                static::macro($method->name, $method->invoke($mixin));
            }
        }
    }

    /**
     * Checks if macro is registered.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public static function hasMacro(string $name) : bool
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Flush the existing macros.
     *
     * @return void
     */
    public static function flushMacros() : void
    {
        static::$macros = [];
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo(null, static::class);
        }

        return $macro(...$parameters);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new \BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        return $macro(...$parameters);
    }
}