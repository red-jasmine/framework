<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
use RedJasmine\Support\Data\Data;

class RefundReshipmentCommand extends Data
{

    public int $rid; // 退款单ID

    /**
     * 快递公司
     *  TODO 改为值对象
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
