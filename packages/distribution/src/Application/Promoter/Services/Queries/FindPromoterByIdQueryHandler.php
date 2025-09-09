<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Queries;

use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindPromoterByIdQueryHandler extends QueryHandler
{
    public function __construct(
        protected PromoterApplicationService $service
    ) {
    }

    public function handle(FindPromoterByIdQuery $query)
    {
        return $this->service->repository->find($query->getKey());
    }
}
