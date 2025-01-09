<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Support\Data\Data;

class RefundData extends Data
{
    public int $merchantAppId;

    public Money $refundAmount;

    public ?string $refundSeason;

    public string $merchantRefundNo;
    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

    public ?string $notifyUrl = null;

    public ?string $passbackParams = null;

}
