<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Common\Exception\RuntimeException;

class GatewayAdapterFactory
{


    public function create(string $className) : GatewayAdapterInterface
    {
        $class = $this->geClassName($className);
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return app($class);

    }

    protected function geClassName(string $className) : string
    {
        if (is_subclass_of($className, GatewayAdapterInterface::class)) {
            return $className;
        }
        // 首字母大写
        $className = ucfirst($className);

        return '\\RedJasmine\\Payment\\Domain\\Gateway\\' . $className . 'GatewayAdapter';


    }
}
