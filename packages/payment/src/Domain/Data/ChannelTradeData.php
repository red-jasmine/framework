<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Money\Data\Money;
use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Models\Enums\TradeStatusEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Payer;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class ChannelTradeData extends Data
{
    public ?string $tradeNo = null;
    public ?Money  $amount;
    public ?Money  $paymentAmount;


    public ?string $channelCode;
    public ?string $channelProductCode;
    public ?string $channelAppId;
    public ?string $channelMerchantId;
    public ?string $channelTradeNo;
    public ?string $sceneCode;
    public ?string $methodCode;

    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $paidTime;

    public ?TradeStatusEnum $status;

    public ?Payer $payer;


    // 补充更多信息
    // TODO  支付人,支付状态,支付时间等

    public ?PaymentTrigger $paymentTrigger = null;
    public ?string         $purchaseResult;


    /**
     * 原始参数信息
     * @var array
     */
    public array $originalParameters = [];


}
