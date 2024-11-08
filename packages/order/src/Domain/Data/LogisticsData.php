<?php

namespace RedJasmine\Order\Domain\Data;

use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
use RedJasmine\Support\Data\Data;

class LogisticsData extends Data
{
    /**
     * 快递公司
     * @var string
     */
    public string $expressCompanyCode;

    /**
     * 快递单号
     * @var string|int
     */
    public string|int $expressNo;


    public LogisticsStatusEnum $status = LogisticsStatusEnum::CREATED;
}
