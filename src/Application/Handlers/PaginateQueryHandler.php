<?php

namespace RedJasmine\Support\Application\Handlers;

use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Application\QueryHandler;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

class PaginateQueryHandler extends QueryHandler
{

    public function handle(?PaginateQuery $query = null) : LengthAwarePaginator
    {

        return $this->getService()->getRepository()->setQueryCallbacks($this->getService()->getQueryCallbacks())->paginate($query);

    }

}
