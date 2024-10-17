<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Support\Data\Data;

class AfterSalesService extends Data
{

    /**
     * 服务类型
     * @var RefundTypeEnum
     */
    public RefundTypeEnum $refundType;


    /**
     * 允许阶段
     * @var OrderAfterSaleServiceAllowStageEnum
     */
    public OrderAfterSaleServiceAllowStageEnum $allowStage = OrderAfterSaleServiceAllowStageEnum::NEVER;


    /**
     * 限制时间
     * @var int
     */
    public int $timeLimit = 0;


    /**
     * 限制时长
     * @var OrderAfterSaleServiceTimeUnit
     */
    public OrderAfterSaleServiceTimeUnit $timeLimitUnit = OrderAfterSaleServiceTimeUnit::HOUR;




}
