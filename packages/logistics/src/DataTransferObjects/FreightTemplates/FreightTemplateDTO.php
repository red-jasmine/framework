<?php

namespace RedJasmine\Logistics\DataTransferObjects\FreightTemplates;

use RedJasmine\Support\Data\Data;
use Spatie\LaravelData\DataCollection;

class FreightTemplateDTO extends Data
{

    public string $name;

    public \RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightChargeTypeEnum $chargeType;

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
