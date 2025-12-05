<?php

namespace RedJasmine\Payment\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Money\Data\Money;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class ChannelRefundData extends Data
{
    public ?string $tradeNo  = null;
    public ?string $refundNo = null;
    public ?Money  $refundAmount;

    public ?string $channelAppId;
    public ?string $channelMerchantId;
    public ?string $channelTradeNo;
    public ?string $channelRefundNo = null;

    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $refundTime;

    public ?RefundStatusEnum $status;


}
