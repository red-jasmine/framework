<?php

namespace RedJasmine\Payment\Domain\Data;

use RedJasmine\Support\Data\Data;

class SettleData extends Data
{
    public int $merchantAppId;

    public string $tradeNo;

    public string $merchantSettleNo;

    public bool $isFinish = false;

    /**
     * @var SettleDetailData[]
     */
    public array $details = [];

}
