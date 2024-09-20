<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

class SimplePaginateQueryHandler extends QueryHandler
{


    public function handle(PaginateQuery $query) : \Illuminate\Contracts\Pagination\Paginator
    {
        /**
         * @var ReadRepositoryInterface $readRepository
         */
        $readRepository = $this->getService()->hook('simplePaginate.repository',
            $query,
            fn() => $this->getService()->getRepository());

        return $readRepository->simplePaginate($query);


    }

}
