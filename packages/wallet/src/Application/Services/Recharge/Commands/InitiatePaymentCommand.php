<?php

namespace RedJasmine\Wallet\Application\Services\Recharge\Commands;

use RedJasmine\Support\Contracts\UserInterface;

class InitiatePaymentCommand
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
     * 支付URL
     */
    public ?string $paymentUrl = null;

    /**
     * 支付二维码
     */
    public ?string $paymentQrCode = null;

    /**
     * 扩展信息
     */
    public array $extra = [];

    /**
     * 操作人
     */
    public UserInterface $operator;
} 