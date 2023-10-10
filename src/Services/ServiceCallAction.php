<?php

namespace RedJasmine\Support\Services;

use BadMethodCallException;
use Illuminate\Support\Traits\Macroable;
use RedJasmine\Support\Contracts\WithOperatorInfoInterface;

/**
 * 子服务调用
 */
trait ServiceCallAction
{
    use Macroable {
        __call as macroCall;
    }

    // 扩展
    public static function extends($name, $class) : void
    {
        static::$actions[$name] = $class;

    }

    /**
     * @return $this
     */
    public function make()
    {
        return $this;
    }


    public function __call(string $method, array $arguments)
    {

        if (static::hasMacro($method)) {
            return $this->macroCall($method, $arguments);
        }

        $actions = $this->getActions();
        $class = $actions[$method] ?? null;
        if (!$class) {
            throw new BadMethodCallException('Call to undefined method ' . $method);
        }
        $service = app($class);

        if ($service instanceof WithOperatorInfoInterface) {
            $service->setOperator($this->getOperator());
            $service->setClient($this->getClient());
        }
        if (method_exists($service, $method)) {
            return $service->{$method}(...$arguments);
        }
        if (method_exists($service, 'handle')) {
            return $service->handle(...$arguments);
        }
        throw new BadMethodCallException('Call to undefined method ' . $method);
    }

    /**
     * 获取操作
     * @return array
     */
    protected function getActions() : array
    {
        $actions = [];

        if (property_exists($this, 'actions')) {
            $actions = self::$actions;
        }
        return $actions;
    }

}
