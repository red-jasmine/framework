<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Data\Data;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;

class FindQueryHandler extends QueryHandler
{


    public function handle(int $id, ?FindQuery $query = null) : mixed
    {
        $readRepository = $this->getService()->hook('repository',
            [$id, $query],
            fn() => $this->getService()->getRepository()->setQueryCallbacks($this->getService()->getQueryCallbacks()));
        return $readRepository->find($id, $query);

    }
}
