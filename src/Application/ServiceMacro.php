<?php

namespace RedJasmine\Support\Application;

use BadMethodCallException;
use Illuminate\Support\Traits\Macroable;


trait ServiceMacro
{

    use Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * Dynamically handle calls to the class.
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
        if (!static::hasMacro($method)) {

            throw new BadMethodCallException(sprintf(
                                                 'Method %s::%s does not exist.', static::class, $method
                                             ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        if (method_exists($this, 'makeMacro')) {
            $macro = $this->makeMacro($macro);
        }
        if ($macro instanceof CommandHandler) {
            // TODO 调用 可以做依赖注入
            return $macro->setArguments($parameters)->handle(...$parameters);
        }
        return $macro(...$parameters);
    }


}
