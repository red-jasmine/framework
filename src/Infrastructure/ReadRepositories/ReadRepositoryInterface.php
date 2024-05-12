<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReadRepositoryInterface
{

    public function find($id, FindQuery $findQuery = null);

    public function paginate(?PaginateQuery $findQuery = null) : LengthAwarePaginator;

    public function simplePaginate(?PaginateQuery $findQuery = null) : Paginator;

}
