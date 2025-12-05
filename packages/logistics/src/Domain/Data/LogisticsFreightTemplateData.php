<?php

namespace RedJasmine\Logistics\Domain\Data;

use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightChargeTypeEnum;
use RedJasmine\Support\Domain\Contracts\UserInterface;
use RedJasmine\Support\Foundation\Data\Data;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;

class LogisticsFreightTemplateData extends Data
{


    public UserInterface $owner;
    /**
     *
     * @var FreightChargeTypeEnum
     */
    #[WithCast(EnumCast::class, FreightChargeTypeEnum::class)]
    public FreightChargeTypeEnum $chargeType;

    public int    $sort   = 0;
    public bool   $isFree = false;
    public string $name;


    /**
     * @var LogisticsFreightTemplateStrategyData[]|null
     */
    public ?array $strategies = null;
}