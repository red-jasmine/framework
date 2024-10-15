<?php

namespace RedJasmine\Logistics\DataTransferObjects\FreightTemplates;

use RedJasmine\Support\Data\Data;

class FreightTemplateFeeRegionDTO extends Data
{

    public string $regions;

    public string|int|float $startStandard;

    public string|int|float $startFee;

    public string|int|float $addStandard;

    public string|int|float $addFee;
}
