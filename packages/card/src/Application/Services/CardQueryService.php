<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Repositories\CardReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class CardQueryService extends ApplicationQueryService
{


    public function __construct(
        protected CardReadRepositoryInterface $repository,
    )
    {

    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::exact('is_loop'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('group_id'),
        ];
    }

    public function allowedIncludes() : array
    {
        return [ 'group' ];
    }


}
