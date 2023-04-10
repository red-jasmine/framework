<?php

namespace RedJasmine\Support\Services;

use Illuminate\Foundation\PackageManifest;
use RedJasmine\Support\Contracts\WithOperatorInfoInterface;

/**
 * 子服务调用
 */
trait ServiceCallAction
{
    /**
     * 关联 操作人
     */
    use OperatorActionService;


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
            $service->setOperator($this->operator);
            $service->setClient($this->client);
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
        if (property_exists($this, 'composerConfigName')) {
            $composerActions = app(PackageManifest::class)->config(self::$composerConfigName)['actions'] ?? [];
            $actions         = array_merge($actions, $composerActions);
        }
        return $actions;
    }

}
