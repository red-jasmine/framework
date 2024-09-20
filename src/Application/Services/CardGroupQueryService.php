<?php

namespace RedJasmine\Card\Application\Services;

use RedJasmine\Card\Domain\Repositories\CardGroupReadRepositoryInterface;
use RedJasmine\Support\Application\ApplicationQueryService;
use Spatie\QueryBuilder\AllowedFilter;

class CardGroupQueryService extends ApplicationQueryService
{


    public function __construct(
        protected CardGroupReadRepositoryInterface $repository,
    )
    {

    }


    public function allowedFilters() : array
    {

        return [
            AllowedFilter::exact('owner_type'),
            AllowedFilter::exact('owner_id'),
            AllowedFilter::partial('name'),
        ];
    }

}
