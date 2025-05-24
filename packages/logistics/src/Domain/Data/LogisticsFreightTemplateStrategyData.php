<?php

namespace RedJasmine\Logistics\Domain\Data;

use Cknow\Money\Money;
use RedJasmine\Logistics\Domain\Models\Enums\FreightTemplates\FreightTemplateStrategyTypeEnum;
use RedJasmine\Support\Data\Data;

class LogisticsFreightTemplateStrategyData extends Data
{

    public ?int $id = null;


    public FreightTemplateStrategyTypeEnum $type;


    public bool $isAllRegions = false;
    /**
     * @var array<string>
     */
    public array $regions = [];

    public string|float|int $standardQuantity = 1;

    public ?Money $standardFee;

    public string|float|int $extraQuantity = 0;

    public ?Money $extraFee;
}