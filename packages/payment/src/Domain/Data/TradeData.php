<?php

namespace RedJasmine\Payment\Domain\Data;

use DateTime;
use RedJasmine\Payment\Domain\Models\ValueObjects\Store;
use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\ValueObjects\Money;

class TradeData extends Data
{

    public int $merchantAppId;

    public string $merchantTradeNo;

    public ?string $merchantTradeOrderNo;

    public Money $amount;

    public string $subject;
    /**
     * 是否需要结算分账
     * @var bool
     */
    public bool   $isSettleSharing = false;

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
