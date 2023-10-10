<?php

namespace RedJasmine\Support\Services;

use Illuminate\Foundation\PackageManifest;
use RedJasmine\Support\Contracts\WithOperatorInfoInterface;

/**
 * 子服务调用
 */
trait ServiceCallAction
{



    public function __call(string $name, array $arguments)
    {
        $actions = $this->getActions();

        $serviceConfig = $actions[$name] ?? null;
        if (is_string($serviceConfig)) {
            $serviceConfig = [ $serviceConfig, $name ];
        }
        [ $serviceClass, $method ] = $serviceConfig;

        if (!$serviceClass) {
            throw new \BadMethodCallException('Call to undefined method ' . $name);
        }
        $service = app($serviceClass);

        if ($service instanceof WithOperatorInfoInterface) {
            $service->setOperator($this->getOperator());
            $service->setClient($this->getClient());
        }
        if (method_exists($service, $method)) {
            return $service->{$method}(...$arguments);
        }
        return $service->handle(...$arguments);
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
