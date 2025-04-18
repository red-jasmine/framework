<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class RegionChildrenQuery extends PaginateQuery
{

    public ?string $countryCode = null;

    public string $parentCode = '0';
}