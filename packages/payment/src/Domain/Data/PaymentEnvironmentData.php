<?php

namespace RedJasmine\Payment\Domain\Data;

use Omnipay\Common\PaymentMethod;
use RedJasmine\Support\Data\Data;

/**
 * 支付环境
 * 支付环境会影响支付可选的平台，需要根据环境来选择支付方式
 */
class PaymentEnvironmentData extends Data
{
    /**
     * 支付场景
     * 小程序下 用 JSAPI
     * 应用内 用 JSAPI
     * APP 内用 APP
     * H5 用 H5
     * WEB 用 WEB
     * ...
     * @var PaymentMethod
     */
    public PaymentMethod $method;

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


    /**
     * 选择的支付方式
     * @var string|null
     */
    public ?string $method;
}
