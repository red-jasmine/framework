<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceAllowStageEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\OrderAfterSaleServiceTimeUnit;
use RedJasmine\Ecommerce\Domain\Models\Enums\RefundReasonTypeEnum;
use RedJasmine\Ecommerce\Domain\Models\Enums\StrategyTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
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
    public OrderAfterSaleServiceAllowStageEnum $start = OrderAfterSaleServiceAllowStageEnum::PAYED;


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
     * 是否限制理由
     * false: 不限制，允许所有理由类型
     * true: 限制，根据 $reasonTypeScopes 白名单判断
     * @var bool
     */
    public bool $isReasonTypeRestricted = false;

    /**
     * 允许的理由类型
     * @var RefundReasonTypeEnum[]
     */
    public array $reasonTypeScopes = [];


    /**
     * 判断是否允许指定的理由类型
     * @param RefundReasonTypeEnum $reasonType
     * @return bool
     */
    public function isReasonTypeAllowed(RefundReasonTypeEnum $reasonType): bool
    {
        // 不限制，允许所有理由类型
        if (!$this->isReasonRestricted) {
            return true;
        }

        // 限制模式：空数组表示不允许任何理由类型
        if (empty($this->allowedReasons)) {
            return false;
        }

        // 检查是否在白名单中
        return in_array($reasonType, $this->allowedReasons, true);
    }

    public function getAddValue() : string
    {
        return $this->timeLimit.$this->timeLimitUnit->value;
    }


}
