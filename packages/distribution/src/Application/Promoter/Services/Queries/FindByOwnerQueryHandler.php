<?php

namespace RedJasmine\Distribution\Application\Promoter\Services\Queries;

use RedJasmine\Distribution\Application\Promoter\Services\PromoterApplicationService;
use RedJasmine\Distribution\Domain\Models\Promoter;
use RedJasmine\Support\Application\Queries\QueryHandler;

class FindByOwnerQueryHandler extends QueryHandler
{
    public function __construct(
        protected PromoterApplicationService $service
    ) {
    }

    public function handle(FindByOwnerQuery $query) : Promoter
    {
        return $this->service->readRepository->findByOwner($query);
    }
} 