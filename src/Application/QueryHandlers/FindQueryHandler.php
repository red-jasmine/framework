<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class FindQueryHandler extends QueryHandler
{


    public function handle(int $id, ?FindQuery $query = null) : mixed
    {
        $readRepository = $this->getService()->hook('find.repository',
            [$id, $query],
            fn() => $this->getService()->getRepository()->setQueryCallbacks($this->getService()->getQueryCallbacks()));
        return $readRepository->find($id, $query);

    }
}
