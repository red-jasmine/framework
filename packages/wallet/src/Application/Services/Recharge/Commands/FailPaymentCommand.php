<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use RedJasmine\Support\Domain\Contracts\UserInterface;

class FailPaymentCommand
{
    /**
     * 充值单ID
     */
    public int $rechargeId;

    /**
     * 支付订单号
     */
    public string $paymentOrderNo;

    /**
     * 支付平台订单号
     */
    public ?string $platformOrderNo = null;

    /**
     * 失败原因
     */
    public string $failReason;

    /**
     * 失败代码
     */
    public ?string $failCode = null;

    /**
     * 支付平台返回的原始数据
     */
    public array $platformResponse = [];

    /**
     * 扩展信息
     */
    public array $extra = [];

    /**
     * 操作人
     */
    public UserInterface $operator;
} 