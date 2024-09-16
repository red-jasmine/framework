<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class SimplePaginateQueryHandler extends QueryHandler
{


    public function handle(PaginateQuery $query) : \Illuminate\Contracts\Pagination\Paginator
    {
        /**
         * @var ReadRepositoryInterface $readRepository
         */
        $readRepository = $this->getService()->hook('simplePaginate.repository',
            $query,
            fn() => $this->getService()->getRepository()->setQueryCallbacks($this->getService()->getQueryCallbacks()));

        return $readRepository->simplePaginate($query);


    }

}
