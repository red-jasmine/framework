<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class PaginateQueryHandler extends QueryHandler
{


    public function handle(PaginateQuery $query)
    {
        $readRepository = $this->getService()->hook('repository',
            $query,
            fn() => $this->getService()->getRepository()->setQueryCallbacks($this->getService()->getQueryCallbacks()));

        return $readRepository->paginate($query);


    }

}
