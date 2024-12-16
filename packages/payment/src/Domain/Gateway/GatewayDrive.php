<?php

namespace RedJasmine\Payment\Domain\Gateway;

use RedJasmine\Payment\Domain\Models\ChannelApp;

class GatewayDrive
{

    /**
     * Get the gateway factory
     *
     * Creates a new empty GatewayFactory if none has been set previously.
     *
     * @return GatewayDriveFactory A GatewayFactory instance
     */
    public static function getFactory() : GatewayDriveFactory
    {
        if (is_null(self::$factory)) {
            self::$factory = new GatewayDriveFactory;
        }

        return self::$factory;
    }

    /**
     * Internal factory storage
     *
     * @var GatewayDriveFactory
     */
    private static GatewayDriveFactory $factory;

    public static function create(string $className) : GatewayDriveInterface
    {

        // 根据渠道应用 创建 支付网关适配器

        $factory = self::getFactory();
        return $factory->create($className);
    }

}
