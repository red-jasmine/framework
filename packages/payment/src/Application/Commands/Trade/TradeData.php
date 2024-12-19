<?php

namespace RedJasmine\Payment\Application\Commands\Trade;

use DateTime;
use RedJasmine\Payment\Domain\Data\GoodDetailData;
use RedJasmine\Payment\Domain\Models\ValueObjects\Money;
use RedJasmine\Payment\Domain\Models\ValueObjects\Store;
use RedJasmine\Support\Data\Data;

class TradeData extends Data
{

    public int $merchantAppId;

    public string $merchantTradeNo;

    public ?string $merchantOrderNo;

    public Money $amount;

    public string $subject;

    public ?string $description = null;

    public ?DateTime $expiredTime = null;

    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];
    // 门店信息

    public ?Store $store = null;

    public ?string $notifyUrl = null;

    public ?string $returnUrl = null;

    public ?string $passBackParams = null;


}
