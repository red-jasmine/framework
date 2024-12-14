<?php

namespace RedJasmine\Payment\Domain\Gateway;

use RedJasmine\Payment\Domain\Models\ChannelApp;

class GatewayAdapter
{

    /**
     * Get the gateway factory
     *
     * Creates a new empty GatewayFactory if none has been set previously.
     *
     * @return GatewayAdapterFactory A GatewayFactory instance
     */
    public static function getFactory() : GatewayAdapterFactory
    {
        if (is_null(self::$factory)) {
            self::$factory = new GatewayAdapterFactory;
        }

        return self::$factory;
    }

    /**
     * Internal factory storage
     *
     * @var GatewayAdapterFactory
     */
    private static GatewayAdapterFactory $factory;

    public static function create(ChannelApp $channelApp) : GatewayAdapterInterface
    {
        // 根据渠道应用 创建 支付网关适配器
        $channelApp->channel_code;
        $factory = self::getFactory();
        return $factory->create((string)$channelApp->channel_code);
    }

}
