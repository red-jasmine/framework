<?php

namespace RedJasmine\Support\Traits\Services;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait HasQueryBuilder
{


    /**
     *
     *
     * AllowedFilter::callback('keyword', function ($query, $value, $property) {
     *     $query->where(function ($query) use ($value, $property) {
     *         $query->where('name', 'like', '%' . $value . '%')
     *               ->orWhere('logo', 'like', '%' . $value . '%');
     *
     *     });
     * }),
     * @return array
     */
    public function filters() : array
    {
        return [

        ];
    }

    public function includes() : array
    {
        return [];
    }

    public function fields() : array
    {
        return [

        ];
    }

    public function sorts() : array
    {
        return [];
    }

    public function query() : QueryBuilder
    {
        $queryBuilder = QueryBuilder::for($this->model);
        if (filled($this->filters())) {
            $queryBuilder->allowedFilters($this->filters());
        }
        $queryBuilder->allowedFields($this->fields());
        $queryBuilder->allowedIncludes($this->includes());
        $queryBuilder->allowedSorts($this->sorts());
        return $queryBuilder;
    }


}
