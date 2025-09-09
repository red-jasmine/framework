<?php

namespace RedJasmine\Region\Application\Services\Country;

use RedJasmine\Region\Application\Services\Country\Queries\CountryFindQuery;
use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

/**
 * @method Country find(CountryFindQuery $query)
 */
class CountryApplicationService extends ApplicationService
{
    protected static string $modelClass = Country::class;

    public function __construct(
        public CountryRepositoryInterface $repository,
    ) {
    }
}
