<?php

namespace RedJasmine\Distribution\Domain\Data;


use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyMethodEnum;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class PromoterApplyData extends Data
{
    
    /**
     * 申请类型
     * @var PromoterApplyTypeEnum
     */
    #[WithCast(EnumCast::class, PromoterApplyTypeEnum::class)]
    public PromoterApplyTypeEnum $applyType = PromoterApplyTypeEnum::REGISTER;

    /**
     * 申请等级
     * @var int
     */
    public int $level = 1;

    /**
     * 申请方式
     * @var PromoterApplyMethodEnum
     */
    #[WithCast(EnumCast::class, PromoterApplyMethodEnum::class)]
    public PromoterApplyMethodEnum $applyMethod = PromoterApplyMethodEnum::AUTO;

}