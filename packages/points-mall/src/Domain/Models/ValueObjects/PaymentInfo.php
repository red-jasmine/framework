<?php

namespace RedJasmine\PointsMall\Domain\Models\ValueObjects;

use RedJasmine\PointsMall\Domain\Models\Enums\PointsProductPaymentModeEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\ValueObject;

class PaymentInfo extends ValueObject
{
    public PointsProductPaymentModeEnum $paymentMode;
    public string $paymentStatus;
    public int $pointAmount;
    public float $moneyAmount;
    public string $paymentMethod;
    public ?string $tradeNo;

    public function __construct(
        PointsProductPaymentModeEnum $paymentMode,
        int $pointAmount = 0,
        float $moneyAmount = 0.0,
        string $paymentMethod = '',
        ?string $tradeNo = null
    ) {
        $this->paymentMode = $paymentMode;
        $this->pointAmount = $pointAmount;
        $this->moneyAmount = $moneyAmount;
        $this->paymentMethod = $paymentMethod;
        $this->tradeNo = $tradeNo;
        $this->paymentStatus = 'pending';
    }

    /**
     * 检查是否为纯积分支付
     */
    public function isPointsOnly() : bool
    {
        return $this->paymentMode === PointsProductPaymentModeEnum::POINTS;
    }


    /**
     * 检查是否为混合支付
     */
    public function isMixed() : bool
    {
        return $this->paymentMode === PointsProductPaymentModeEnum::MIXED;
    }

    /**
     * 获取总支付金额（积分转换为现金）
     */
    public function getTotalMoneyAmount(float $pointsRate = 0.01) : float
    {
        $pointsMoney = $this->pointAmount * $pointsRate;
        return $pointsMoney + $this->moneyAmount;
    }

    /**
     * 设置支付状态
     */
    public function setPaymentStatus(string $status) : void
    {
        $this->paymentStatus = $status;
    }

    /**
     * 设置交易号
     */
    public function setTradeNo(string $tradeNo) : void
    {
        $this->tradeNo = $tradeNo;
    }

    /**
     * 检查是否已支付
     */
    public function isPaid() : bool
    {
        return $this->paymentStatus === 'paid';
    }

    /**
     * 检查是否支付失败
     */
    public function isFailed() : bool
    {
        return $this->paymentStatus === 'failed';
    }

    /**
     * 检查是否待支付
     */
    public function isPending() : bool
    {
        return $this->paymentStatus === 'pending';
    }

    /**
     * 获取支付模式标签
     */
    public function getPaymentModeLabel() : string
    {
        return $this->paymentMode->label();
    }

    /**
     * 获取支付模式颜色
     */
    public function getPaymentModeColor() : string
    {
        return $this->paymentMode->color();
    }
} 