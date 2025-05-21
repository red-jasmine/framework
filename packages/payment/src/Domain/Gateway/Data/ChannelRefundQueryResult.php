<?php

namespace RedJasmine\Payment\Domain\Gateway\Data;

use Illuminate\Support\Carbon;
use RedJasmine\Payment\Domain\Models\Enums\RefundStatusEnum;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

/**
 * 渠道退款查询结果
 */
class ChannelRefundQueryResult extends AbstractChannelResult
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

    /**
     * 原始参数信息
     * @var array
     */
    public array $originalParameters = [];


}
