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

    protected static array $globalActions = [];

    public static function getGlobalActions() : array
    {
        return self::$globalActions;
    }

    public static function setGlobalActions(array $globalActions) : void
    {
        self::$globalActions = $globalActions;
    }

    /**
     * 扩展 操作
     *
     * @param string                 $name
     * @param string|object|callable $action
     *
     * @return void
     */
    public static function extendAction(string $name, string|object|callable $action) : void
    {
        static::$globalActions[$name] = $action;
    }


    protected array $actions = [];

    protected function addAction(string $name, string|object|callable $action) : static
    {
        $this->actions[$name] = $action;
        return $this;
    }

    public function getActions() : array
    {
        return $this->actions;
    }

    public function setAction(array $actions) : static
    {
        $this->actions = $actions;
        return $this;
    }

    protected function actions() : array
    {
        return array_merge($this->actions, static::$globalActions);
    }

    /**
     * 配置 KEY
     * @var string|null
     */
    protected static ?string $actionsConfigKey = null;


    protected static function getConfigActions() : array
    {
        if (blank(static::$actionsConfigKey)) {
            return [];
        }
        return Config::get(static::$actionsConfigKey, []);
    }


    /**
     * Checks if macro is registered.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasAction(string $name) : bool
    {
        $actions = $this->actions();
        return isset($actions[$name]);
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

        if ($this->hasAction($method)) {
            return $this->actionCall($method, $parameters);
        }
        throw new BadMethodCallException(sprintf(
                                             'Method %s::%s does not exist.', static::class, $method
                                         ));
    }


    protected function actionCall($method, $parameters) : mixed
    {
        $action = $this->actions()[$method];
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

    protected function makeAction($name)
    {
        $action           = $this->actions()[$name];
        $action           = app($action);
        $action->callName = $name;
        if ($action instanceof ServiceAwareAction) {
            $action->setService($this);
        }
        // 如果是有
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


    private array $actionObjects = [];


    public function __get(string $name)
    {
        if (static::hasAction($name)) {
            return $this->actionObjects[$name] = $this->actionObjects[$name] ?? $this->getAction($name);

        }
        throw new MissingAttributeException($this, $name);
    }

}
