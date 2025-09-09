<?php

namespace RedJasmine\Region\Application\Services\Region\Queries;

use RedJasmine\Region\Application\Services\Region\RegionApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class RegionChildrenQueryHandler extends QueryHandler
{

    public function __construct(
        protected RegionApplicationService $service

    ) {
    }

    public function handle(RegionChildrenQuery $query) : array
    {
        $query->setIsWithCount(false);
        return $this->service->repository->children($query);
    }

}
