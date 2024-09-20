<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Data\Queries\FindQuery;

class FindQueryHandler extends QueryHandler
{


    public function handle(FindQuery $query) : mixed
    {
        $readRepository = $this->getService()->hook('find.repository',
            $query,
            fn() => $this->getService()->getRepository());
        return $readRepository->find($query);

    }
}
