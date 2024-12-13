<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Common\Exception\RuntimeException;

class GatewayAdapterFactory
{


    public function create($className): GatewayAdapterInterface
    {
        $class = $this->geClassName($className);
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return app($class);

    }

    protected function geClassName($className)
    {
        if (is_subclass_of($className, GatewayAdapterInterface::class)) {
            return $className;
        }
        // TODO 根据名称生成

        return $className;

    }
}
