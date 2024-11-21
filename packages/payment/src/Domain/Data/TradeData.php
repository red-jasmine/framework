<?php

namespace RedJasmine\Payment\Domain\Data;

use DateTime;
use RedJasmine\Support\Data\Data;

class TradeData extends Data
{

    public string $merchantOrderNo;

    public int $merchantAppId;

    public string $amount;

    public string $subject;

    public string $description;

    public string $currency = 'CNY';

    public ?DateTime $expiredTime = null;

    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];

    // 场景信息
    // 门店信息
    // 商户信息

}
