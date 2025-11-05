<?php

namespace RedJasmine\Region\Application\Services\Country\Queries;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class CountryFindQuery extends FindQuery
{
    protected string $primaryKey = 'code';

    /**
     * 国家代码 ISO 3166-1 alpha-2
     */
    public string $code;
}
