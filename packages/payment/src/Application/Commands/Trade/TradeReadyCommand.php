<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Support\Data\Data;

/**
 * 支付准备
 * 通过支付准备命令，返回可选的支付平台如：微信、支付宝、银联等
 */
class TradeReadyCommand extends Data
{
    public int $id;

    /**
     * 支付方式
     * 小程序下 用 JSAPI
     * 应用内 用 JSAPI
     * APP 内用 APP
     * H5 用 H5
     * WEB 用 WEB
     * ...
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
     * 如：微信、支付宝、浏览器、其他App
     * @var string
     */
    public string $client;


}
