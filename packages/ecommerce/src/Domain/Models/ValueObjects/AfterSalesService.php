<?php

namespace RedJasmine\Ecommerce\Domain\Models\ValueObjects;

use RedJasmine\Ecommerce\Domain\Models\Enums\RefundTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
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
     * 是否允许
     * 支持整体关闭
     * @var bool
     */
    public bool $isAllowed = false;

    /**
     * 策略
     *
     * @var AfterSalesStrategy[]
     */
    public array $strategies = [];


}
