<?php

namespace RedJasmine\Region\Infrastructure\Repositories\Eloquent;

use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

class CountryRepository extends Repository implements CountryRepositoryInterface
{

    protected static string $modelClass = Country::class;

}