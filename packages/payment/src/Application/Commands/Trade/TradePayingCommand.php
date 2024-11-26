<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Support\Data\Data;

/**
 * 发起支付
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
     * 支付方式
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
