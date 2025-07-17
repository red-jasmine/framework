<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use Cknow\Money\Money;
use RedJasmine\Support\Data\Data;

class CompletePaymentCommand extends Data
{

    /**
     * 支付订单号
     */
    public string $paymentOrderNo;

    /**
     * 支付平台订单号
     */
    public string $platformOrderNo;

    /**
     * 实际支付金额
     */
    public Money $actualPaymentAmount;

    /**
     * 支付时间
     */
    public string $paidAt;

    /**
     * 支付平台返回的原始数据
     */
    public array $platformResponse = [];

    /**
     * 扩展信息
     */
    public array $extra = [];

} 