<?php

namespace RedJasmine\Logistics\DataTransferObjects\FreightTemplates;

use RedJasmine\Support\DataTransferObjects\Data;

class FreightTemplateFreeRegionDTO extends Data
{

    public string $regions;

    public int $quantity = 0;

    public string|int|float $amount = 0;

}
