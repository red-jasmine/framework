<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class FindQueryHandler extends QueryHandler
{

    public function __construct(
        protected $service
    ) {
    }

    public function handle(FindQuery $query) : mixed
    {
        return $this->service->readRepository->findById($query);
    }
}
