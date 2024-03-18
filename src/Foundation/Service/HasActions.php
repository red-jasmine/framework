<?php

namespace RedJasmine\Support\Foundation\Service;

use Closure;
use http\Exception\BadMethodCallException;
use Illuminate\Database\Eloquent\MissingAttributeException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Traits\Macroable;

trait HasActions
{

    use Macroable {
        __call as macroCall;
    }

    protected static array $actions = [];

    private static bool $loadConfigActions = false;

    protected static ?string $actionsConfigKey = null;

    protected static function getConfigActions() : array
    {
        if (blank(static::$actionsConfigKey)) {
            return [];
        }
        return Config::get(static::$actionsConfigKey, []);
    }

    protected static function loadConfigActions() : void
    {
        if (static::$loadConfigActions === false) {
            static::$actions           = array_merge(static::$actions, static::getConfigActions());
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
        $action = $this->getAction($method);
        if (method_exists($action, 'execute')) {
            return $action->execute(...$parameters);
        }
        // 操作
        return $action;
    }


    private function getAction($name)
    {
        $action           = static::$actions[$name];
        $action           = app($action);
        $action->callName = $name;
        if ($action instanceof ServiceAwareAction) {
            $action->setService($this);
        }
        return $action;
    }


    protected array $actionObject = [];

    public function __get(string $name)
    {
        if (static::hasAction($name)) {
            if (isset($this->actionObject[$name])) {
                return $this->actionObject[$name];
            }
            $this->actionObject[$name] = $this->getAction($name);
            return $this->actionObject[$name];
        }
        throw new MissingAttributeException($this, $name);
    }

}
