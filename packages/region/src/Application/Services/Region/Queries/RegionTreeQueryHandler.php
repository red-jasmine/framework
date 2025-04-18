<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Region\Application\Services\Region\RegionApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class RegionTreeQueryHandler extends QueryHandler
{

    public function __construct(
        protected RegionApplicationService $service

    ) {
    }

    public function handle(RegionTreeQuery $query) : array
    {
        return $this->service->readRepository->tree($query);
    }

}