<?php

namespace RedJasmine\Logistics\DataTransferObjects\FreightTemplates;

use RedJasmine\Logistics\Enums\FreightTemplates\FreightChargeTypeEnum;
use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\DataCollection;

class FreightTemplateDTO extends Data
{

    public string $name;

    public FreightChargeTypeEnum $chargeType;

    public bool $isFree;

    public int $sort = 0;


    /**
     * @var DataCollection<FreightTemplateFeeRegionDTO>|null
     */
    public ?DataCollection $feeRegions = null;


    /**
     * @var DataCollection<FreightTemplateFreeRegionDTO>|null
     */
    public ?DataCollection $freeRegions = null;


}
