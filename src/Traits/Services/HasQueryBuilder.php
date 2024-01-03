<?php

namespace RedJasmine\Support\Traits\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait HasQueryBuilder
{

    protected bool $disableRequest = false;

    public function isDisableRequest() : bool
    {
        return $this->disableRequest;
    }

    public function disableRequest(bool $disableRequest = true) : static
    {
        $this->disableRequest = $disableRequest;
        return $this;
    }


    public static function searchFilter($fields, $name = 'search')
    {
        $fields = is_array($fields) ? $fields : func_get_args();
        if (blank($fields)) {
            return;
        }
        $function = function ($query, $value, $property) use ($fields) {
            $query->where(function ($query) use ($value, $property, $fields) {
                $query->where($fields[0], 'like', '%' . $value . '%');
                unset($fields[0]);
                foreach ($fields as $field) {
                    $query->orWhere($field, 'like', '%' . $value . '%');
                }
            });
        };
        return AllowedFilter::callback($name, $function);

    }

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

    public function queryBuilder() : QueryBuilder
    {
        /**
         * 如果是 不是当前请求调用 会出现 自动加载条件问题
         */
        $request = null;
        if ($this->disableRequest) {
            $request = new Request();
        }
        $queryBuilder = QueryBuilder::for($this->model, $request);
        if (filled($this->filters())) {
            $queryBuilder->allowedFilters($this->filters());
        }
        $queryBuilder->allowedFields($this->fields());
        $queryBuilder->allowedIncludes($this->includes());
        $queryBuilder->allowedSorts($this->sorts());
        return $queryBuilder;
    }


}
