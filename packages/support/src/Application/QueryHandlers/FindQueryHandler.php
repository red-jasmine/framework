<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Application\ApplicationQueryService;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class FindQueryHandler extends QueryHandler
{

    public function __construct(
        protected ApplicationQueryService $service
    ) {
    }

    public function handle(FindQuery $query) : mixed
    {
        $readRepository = $this->getService()->hook('find.repository',
            $query,
            fn() => $this->getService()->getRepository());
        return $readRepository->findById($query);

    }
}
