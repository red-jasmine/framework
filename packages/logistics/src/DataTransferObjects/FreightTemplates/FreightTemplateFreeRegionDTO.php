<?php

namespace RedJasmine\Logistics\DataTransferObjects\FreightTemplates;

use RedJasmine\Support\Data\Data;

class FreightTemplateFreeRegionDTO extends Data
{

    public string $regions;

    public int $quantity = 0;

    public string|int|float $amount = 0;

}
