<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Support\Data\Data;

/**
 * 加载支付
 * 这些因素是会 决定可选的 支付平台
 */
class TradePayingCommand extends Data
{
    public int $id;

    /**
     * 支付平台
     * @var string
     */
    public string $platform;

    /**
     * 支付产品
     * @var string
     */
    public string $method;

    /**
     * 设备
     * 如:PC、手机、平板等
     */
    public string $device;

    /**
     * 客户端
     * 如：微信、支付宝、浏览器
     * @var string
     */
    public string $client;


}
