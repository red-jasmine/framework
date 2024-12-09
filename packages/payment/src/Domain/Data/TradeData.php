<?php

namespace RedJasmine\Payment\Domain\Data;

use DateTime;
use Money\Currency;
use RedJasmine\Support\Data\Data;

class TradeData extends Data
{

    public int $merchantAppId;

    public string $merchantOrderNo;

    public string $currency = 'CNY';

    public int $amount;

    public string $subject;

    public ?string $description = null;

    public ?DateTime $expiredTime = null;

    /**
     * @var GoodDetailData[]
     */
    public array $goodDetails = [];


    // 门店信息
    public ?string $storeType;

    public ?string $storeID;

    public ?string $storeName;


    public ?string $passbackParams = null;


}
