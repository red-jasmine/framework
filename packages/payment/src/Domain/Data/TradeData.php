<?php

namespace RedJasmine\Payment\Domain\Data;

use DateTime;
use RedJasmine\Support\Data\Data;

class TradeData extends Data
{

    public string $merchantOrderNo;

    public int $merchantAppId;

    public string $currency = 'CNY';

    public string $amount;

    public string $subject;

    public string $description;


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
