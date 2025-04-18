<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Region\Domain\Enums\RegionLevelEnum;
use RedJasmine\Support\Domain\Data\Queries\Query;

class RegionTreeQuery extends Query
{

    public string $countryCode = 'CHN';

    public array $level = [
        RegionLevelEnum::PROVINCE->value,
        RegionLevelEnum::CITY->value,
    ];


}