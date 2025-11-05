<?php

namespace RedJasmine\Region\Application\Services\Country;

use RedJasmine\Region\Application\Services\Country\Queries\CountryFindQuery;
use RedJasmine\Region\Domain\Models\Country;
use RedJasmine\Region\Domain\Repositories\CountryRepositoryInterface;
use RedJasmine\Region\Domain\Transformers\CountryTransformer;
use RedJasmine\Support\Application\ApplicationService;

/**
 * 国家应用服务
 *
 * @method Country find(CountryFindQuery $query)
 */
class CountryApplicationService extends ApplicationService
{
    public static string $hookNamePrefix = 'region.country.application';

    protected static string $modelClass = Country::class;

    public function __construct(
        public CountryRepositoryInterface $repository,
        public CountryTransformer $transformer,
    ) {
    }
}
