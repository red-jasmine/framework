<?php

namespace RedJasmine\Wallet\Domain\Data\Recharge;

use Illuminate\Support\Carbon;
use RedJasmine\Money\Data\Money;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class RechargePaymentData extends Data
{
    /**
     * 支付单类型
     * @var string
     */
    public string $paymentType;
    /**
     * 支付单ID
     * @var string
     */
    public string $paymentId;
    /**
     * 支付订单号
     */
    public ?string $paymentChannelTradeNo = null;
    public ?string $paymentMode           = null;
    public ?string $paymentOrderNo        = null;

    /**
     * @var ?Carbon
     */
    #[WithCast(DateTimeInterfaceCast::class, 'Y-m-d H:i:s')]
    public ?Carbon $paymentTime = null;

    /**
     * 实际支付金额
     */
    public ?Money $paymentAmount = null;


    /**
     * 扩展信息
     */
    public array $extra = [];
}