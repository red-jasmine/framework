<?php

namespace RedJasmine\Region\Infrastructure\Repositories\Eloquent;

use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

class CountryRepository extends EloquentRepository implements CountryRepositoryInterface
{

    protected static string $eloquentModelClass = Country::class;

}