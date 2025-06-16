<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Support\Domain\Data\Queries\Query;

class RegionTreeQuery extends Query
{

    public string $countryCode = 'CHN';
    public int    $level       = 3;
    public ?array $type        = [];


}