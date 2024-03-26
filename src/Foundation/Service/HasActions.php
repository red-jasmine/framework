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

    // 配置信息  > 属性的(外部修改)  > 方法的() > 全局
    // 静态的
    /**
     * 静态可扩展
     * @var array
     */
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
        return [];
    }

    protected function mergeActions() : array
    {
        // 配置信息  > 属性的(外部修改)  > 方法的() > 全局
        return array_merge(static::$globalActions,
                           $this->actions(),
                           $this->getActions(),
        // TODO 配置的
        );
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
        $actions = $this->mergeActions();
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
        $actionConfig = $this->mergeActions()[$method];
        if ($actionConfig instanceof Closure) {
            $action = $actionConfig->bindTo($this, static::class);
            return $action(...$parameters);
        }
        $action = $this->makeAction($method);
        if (method_exists($action, 'execute')) {
            return $action->execute(...$parameters);
        }
        // 操作
        return $action;
    }


    protected function makeAction($name) : Action
    {
        $actionConfig   = $this->mergeActions()[$name];
        $abstractAction = null;
        if (is_object($actionConfig)) {
            $action       = $actionConfig;
            $actionConfig = [];
        } else {
            if (is_string($actionConfig)) {
                $abstractAction = $actionConfig;
            } else if (is_array($actionConfig)) {
                $abstractAction = $actionConfig['class'];
            }
            if (blank($abstractAction)) {
                throw new BadMethodCallException(sprintf('Method %s::%s does not exist.', static::class, $name));
            }
            $action = app($abstractAction);
        }

        /**
         * @var Action $action
         */
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
            return $this->actionObjects[$name] = $this->actionObjects[$name] ?? $this->makeAction($name);

        }
        throw new MissingAttributeException($this, $name);
    }

}
