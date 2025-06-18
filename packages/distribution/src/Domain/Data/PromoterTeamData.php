<?php

namespace RedJasmine\Distribution\Domain\Data;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Models\Enums\UniversalStatusEnum;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use RedJasmine\Support\Domain\Data\BaseCategoryData;

class PromoterTeamData extends BaseCategoryData
{

    /**
     * 团长ID
     */
    public ?int $leaderId = null;
}
