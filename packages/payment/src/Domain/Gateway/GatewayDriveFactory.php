<?php

namespace RedJasmine\Payment\Domain\Gateway;

use Omnipay\Common\Exception\RuntimeException;

class GatewayDriveFactory
{


    public function create(string $className) : GatewayDriveInterface
    {
        $class = $this->geClassName($className);
        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        return app($class);

    }

    protected function geClassName(string $className) : string
    {
        if (is_subclass_of($className, GatewayDriveInterface::class)) {
            return $className;
        }
        // 首字母大写
        $className = ucfirst($className);

        return '\\RedJasmine\\Payment\\Infrastructure\\Gateway\\' . $className . 'GatewayDrive';


    }
}
