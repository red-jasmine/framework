<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Commands;

use RedJasmine\Distribution\Domain\Data\PromoterApplyData;
use RedJasmine\Distribution\Domain\Models\Enums\PromoterApplyTypeEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;


class PromoterUpgradeCommand extends PromoterApplyData
{

    /**
     * 申请类型
     * @var PromoterApplyTypeEnum
     */
    #[WithCast(EnumCast::class, PromoterApplyTypeEnum::class)]
    public PromoterApplyTypeEnum $applyType = PromoterApplyTypeEnum::UPGRADE;

    public int $id;

    public int $level;

    public ?string $remarks = null;
} 