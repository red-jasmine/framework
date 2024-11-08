<?php

namespace RedJasmine\Order\Application\UserCases\Commands\Refund;

use RedJasmine\Order\Domain\Models\Enums\Logistics\LogisticsStatusEnum;
use RedJasmine\Support\Data\Data;

class RefundReturnGoodsCommand extends Data
{

    public int $id; // 退款单ID

    /**
     * 快递公司
     *  TODO 改为值对象
     * @var string
     */
    public string $logisticsCompanyCode;

    /**
     * 快递单号
     * @var string|int
     */
    public string|int $logisticsNo;


    public LogisticsStatusEnum $status = LogisticsStatusEnum::CREATED;

}
