<?php

namespace RedJasmine\Payment\Domain\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class ChannelRefundData extends Data
{
    public ?string $tradeNo  = null;
    public ?string $refundNo = null;
    public ?Money  $refundAmount;

    public ?string $channelRefundNo = null;

    public ?string $channelCode;
    public ?string $channelProductCode;
    public ?string $channelAppId;
    public ?string $channelMerchantId;
    public ?string $channelTradeNo;


    #[WithCast(DateTimeInterfaceCast::class)]
    public ?Carbon $refundTime;

    public ?RefundStatusEnum $status;


    /**
     * 原始参数信息
     * @var array
     */
    public array $originalParameters = [];


}
