<?php

namespace RedJasmine\Support\Application\Queries;

use RedJasmine\Support\Application\ApplicationService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

/**
 * @property ApplicationService $service
 */
class FindQueryHandler extends QueryHandler
{

    public function __construct(
        protected $service
    ) {
    }

    public function handle(FindQuery $query) : mixed
    {
        return $this->service->readRepository->find($query);
    }
}
