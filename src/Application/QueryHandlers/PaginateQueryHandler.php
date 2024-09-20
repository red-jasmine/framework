<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;

class PaginateQueryHandler extends QueryHandler
{


    public function handle(PaginateQuery $query) : \Illuminate\Pagination\LengthAwarePaginator
    {
        /**
         * @var $readRepository ReadRepositoryInterface
         */
        $readRepository = $this->getService()->hook('paginate.repository', $query,
            fn() => $this->getService()->getRepository());



        return $readRepository->paginate($query);


    }

}
