<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;

class TradeRefundCreateCommand extends Data
{
    public int $tradeId;
    /**
     * 商户退款单号
     * @var string
     */
    public string $merchantRefundNo;

    public Money $refundAmount;

    public ?string $refundSeason;

    /**
     * 商户原始退款订单号
     * @var ?string
     */
    public ?string $merchantRefundOrderNo = null;
    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

    public ?string $notifyUrl = null;

    public ?string $passbackParams = null;
}
