<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class RefundCreateCommand extends Data
{
    public string $tradeNo;
    /**
     * 商户退款单号
     * @var string
     */
    public string $merchantRefundNo;

    public MoneyOld $refundAmount;

    public ?string $refundReason = null;

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

    public ?string $passBackParams = null;

    /**
     *
     * @var bool
     */
    public bool $isAutoExecute = true;
}
