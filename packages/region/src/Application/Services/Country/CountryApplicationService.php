<?php

namespace RedJasmine\Region\Application\Services\Country;

use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryReadRepositoryInterface;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Support\Application\ApplicationService;

class CountryApplicationService extends ApplicationService
{


    protected static string $modelClass = Country::class;

    public function __construct(
        public CountryRepositoryInterface $repository,
        public CountryReadRepositoryInterface $readRepository,
    ) {
    }
}