<?php

namespace RedJasmine\Region\Application\Services\Country\Queries;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CountryFindQuery extends FindQuery
{

    protected string $primaryKey = 'code';
    public string $code;

}