<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class AfterSalesService extends Data
{

    /**
     * 服务类型
     * @var RefundTypeEnum
     */
    #[WithCast(EnumCast::class, type: RefundTypeEnum::class)]
    public RefundTypeEnum $refundType;


    /**
     * 允许阶段
     * @var OrderAfterSaleServiceAllowStageEnum
     */
    #[WithCast(EnumCast::class, type: OrderAfterSaleServiceAllowStageEnum::class)]
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
    #[WithCast(EnumCast::class, type: OrderAfterSaleServiceTimeUnit::class)]
    public OrderAfterSaleServiceTimeUnit $timeLimitUnit = OrderAfterSaleServiceTimeUnit::Hour;


    public function getAddValue() : string
    {
        return $this->timeLimit . $this->timeLimitUnit->value;
    }

}
