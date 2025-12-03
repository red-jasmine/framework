<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundReasonTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\StrategyTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

/**
 * 售后支持策略
 * 结束时间是 时间限制 或者 结束节点 哪个先到为准
 *
 */
class AfterSalesStrategy extends Data
{

    /**
     * 策略类型
     * @var StrategyTypeEnum
     */
    #[WithCast(EnumCast::class, type: StrategyTypeEnum::class)]
    public StrategyTypeEnum $type;


    // 阶段
    /**
     * 开始节点
     * @var OrderAfterSaleServiceAllowStageEnum
     */
    #[WithCast(EnumCast::class, type: OrderAfterSaleServiceAllowStageEnum::class)]
    public OrderAfterSaleServiceAllowStageEnum $start = OrderAfterSaleServiceAllowStageEnum::NEVER;


    /**
     * 结束节点
     * @var ?OrderAfterSaleServiceAllowStageEnum
     */
    #[WithCast(EnumCast::class, type: OrderAfterSaleServiceAllowStageEnum::class)]
    public ?OrderAfterSaleServiceAllowStageEnum $end = null;

    /**
     * 时间限制
     * @var int
     */
    public int $timeLimit = 0;
    /**
     * 最晚时间单位
     * @var OrderAfterSaleServiceTimeUnit
     */
    #[WithCast(EnumCast::class, type: OrderAfterSaleServiceTimeUnit::class)]
    public OrderAfterSaleServiceTimeUnit $timeLimitUnit = OrderAfterSaleServiceTimeUnit::Hour;


    /**
     * 理由类型
     * @var RefundReasonTypeEnum[]|null
     */
    public ?array $allowedReasons = null;


    public function getAddValue() : string
    {
        return $this->timeLimit.$this->timeLimitUnit->value;
    }


}