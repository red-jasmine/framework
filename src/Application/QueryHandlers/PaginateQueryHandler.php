<?php

namespace RedJasmine\Support\Application\QueryHandlers;

use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class PaginateQueryHandler extends QueryHandler
{

    public function __construct(
        protected ReadRepositoryInterface $readRepository
    ) {
    }


    public function handle(PaginateQuery $query)
    {
        $this->readRepository->paginate($query);

    }

}
