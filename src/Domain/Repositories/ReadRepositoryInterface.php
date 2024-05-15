<?php

namespace RedJasmine\Support\Domain\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use RedJasmine\Support\Infrastructure\ReadRepositories\FindQuery;
use RedJasmine\Support\Infrastructure\ReadRepositories\PaginateQuery;

interface ReadRepositoryInterface
{

    public function find($id, FindQuery $findQuery = null);

    public function paginate(?PaginateQuery $findQuery = null) : LengthAwarePaginator;

    public function simplePaginate(?PaginateQuery $findQuery = null) : Paginator;

}
