<?php

namespace RedJasmine\Region\Application\Services\Country\Queries;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;

class CountryPaginateQuery extends PaginateQuery
{
    public ?string $phoneCode;
    public ?string $code;
    public ?string $name;
    public ?string $region;
    public ?string $currency;


}