<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

/**
 * 支付环境
 */
class PaymentEnvironmentData extends Data
{
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
