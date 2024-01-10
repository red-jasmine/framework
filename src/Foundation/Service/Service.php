<?php

namespace RedJasmine\Support\Foundation\Service;

use BadMethodCallException;
use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Traits\Macroable;

class Service
{

    use WithUserService;

    use WithClientService;

    use Macroable {
        __call as macroCall;
    }

    private static array $actions = [];

    private static bool $loadConfigActions = false;

    protected static string $actionsConfigKey = '';

    protected static function getConfigActions() : array
    {
        return Config::get(static::$actionsConfigKey, []);
    }

    protected static function loadConfigActions() : void
    {
        if (static::$loadConfigActions === false) {
            static::$actions           = array_merge(self::$actions, self::getConfigActions());
            static::$loadConfigActions = true;
        }

    }

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
        static::loadConfigActions();
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
        static::loadConfigActions();
        return isset(static::$actions[$name]);
    }


    public static function getActions() : array
    {
        static::loadConfigActions();
        return static::$actions;
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
        // 操作
        return $action;
    }


}
