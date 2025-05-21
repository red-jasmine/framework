<?php

namespace RedJasmine\Payment\Application\Services\Refund\Commands;

use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\MoneyOld;

class RefundData extends Data
{
    public int $merchantAppId;

    public MoneyOld $refundAmount;

    public ?string $refundSeason;

    public string $merchantRefundNo;
    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

    public ?string $notifyUrl = null;

    public ?string $passbackParams = null;

}
