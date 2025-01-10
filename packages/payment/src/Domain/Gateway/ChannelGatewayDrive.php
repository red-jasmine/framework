<?php

namespace RedJasmine\Payment\Domain\Gateway;

class ChannelGatewayDrive
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
     * @var GatewayDriveFactory|null
     */
    private static ?GatewayDriveFactory $factory = null;

    public static function create(string $className) : GatewayDriveInterface
    {

        // 根据渠道应用 创建 支付网关适配器

        $factory = self::getFactory();
        return $factory->create($className);
    }

}
