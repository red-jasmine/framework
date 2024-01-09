<?php

namespace RedJasmine\Support\Foundation\Service;

use BadMethodCallException;
use Closure;
use Illuminate\Support\Traits\Macroable;

class Service
{

    use WithUserService;

    use WithClientService;

    use Macroable {
        __call as macroCall;
    }

    protected static array $actions = [];

    /**
     * 扩展 操作
     *
     * @param string                 $name
     * @param string|object|callable $action
     *
     * @return void
     */
    public static function extends(string $name, string|object|callable $action) : void
    {
        static::$actions[$name] = $action;
    }

    /**
     * Checks if macro is registered.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function hasAction(string $name) : bool
    {
        return isset(static::$actions[$name]);
    }


    public function getActions()
    {
        // TODO
        // 配置化
        // 扩展化
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
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        if (static::hasAction($method)) {
            return $this->actionCall($method, $parameters);
        }
        throw new BadMethodCallException(sprintf(
                                             'Method %s::%s does not exist.', static::class, $method
                                         ));
    }


    protected function actionCall($method, $parameters) : mixed
    {
        $action = static::$actions[$method];

        if ($action instanceof Closure) {
            $action = $action->bindTo($this, static::class);
            return $action(...$parameters);
        }
        $action = app($action);

        if ($action instanceof ServiceAwareAction) {
            $action->setService($this);
        }
        if (method_exists($action, $method)) {
            return $action->{$method}(...$parameters);
        }
        return $action(...$parameters);
    }


}
