<?php

namespace RedJasmine\Payment\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class ChannelRefundData extends Data
{
    public ?string $tradeNo  = null;
    public ?string   $refundNo = null;
    public ?MoneyOld $refundAmount;

    public ?string $channelAppId;
    public ?string $channelMerchantId;
    public ?string $channelTradeNo;
    public ?string $channelRefundNo = null;

    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $refundTime;

    public ?RefundStatusEnum $status;


}
