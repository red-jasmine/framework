<?php

namespace RedJasmine\Support\Foundation\Service\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RedJasmine\Support\Foundation\Service\Actions;
use RedJasmine\Support\Foundation\Service\HasQueryBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class AbstractQueryAction extends Actions
{

    use HasQueryBuilder;


    /**
     * @return static
     */
    public function execute() : static
    {
        return $this;
    }

    public function query() : QueryBuilder
    {
        return $this->queryBuilder();
    }


    /**
     * @return Collection|array
     */
    public function get() : Collection|array
    {
        return $this->query()->get();
    }

    public function find($id) : Model
    {
        return $this->query()->find($id);
    }

    public function paginate() : LengthAwarePaginator
    {
        return $this->query()->paginate();
    }

    public function simplePaginate() : LengthAwarePaginator
    {
        return $this->query()->simplePaginate();
    }
}
