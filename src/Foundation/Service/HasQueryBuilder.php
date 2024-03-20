<?php

namespace RedJasmine\Support\Foundation\Service;

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


    protected array $filters  = [];
    protected array $includes = [];
    protected array $fields   = [];
    protected array $sorts    = [];

    public function setFilters(array $filters) : static
    {
        $this->filters = $filters;
        return $this;
    }

    public function setIncludes(array $includes) : static
    {
        $this->includes = $includes;
        return $this;
    }

    public function setFields(array $fields) : static
    {
        $this->fields = $fields;
        return $this;
    }

    public function setSorts(array $sorts) : static
    {
        $this->sorts = $sorts;
        return $this;
    }

    protected function filters() : array
    {
        return $this->filters;
    }

    protected function includes() : array
    {
        return $this->includes;
    }

    protected function fields() : array
    {
        return $this->fields;
    }

    protected function sorts() : array
    {
        return $this->sorts;
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
        $queryBuilder = QueryBuilder::for($this->service::getModel(), $request);
        if (filled($this->filters())) {
            $queryBuilder->allowedFilters($this->filters());
        }
        if (filled($this->fields())) {
            $queryBuilder->allowedFields($this->fields());
        }
        if (filled($this->includes())) {
            $queryBuilder->allowedIncludes($this->includes());
        }

        if (filled($this->sorts())) {
            $queryBuilder->allowedSorts($this->sorts());
        }

        return $queryBuilder;
    }


}
