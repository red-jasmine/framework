<?php

namespace RedJasmine\Support\Foundation\Service;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

trait HasQueryBuilder
{
    protected function initializeHasQueryBuilder() : void
    {
        $this->filters  = array_merge($this->filters, $this->filters());
        $this->includes = array_merge($this->includes, $this->includes());
        $this->sorts    = array_merge($this->includes, $this->sorts());
        $this->fields   = array_merge($this->fields, $this->fields());
        $this->select   = array_merge($this->select, $this->select());

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
    protected array $select   = [];

    public function getSelect() : array
    {
        return $this->select;
    }

    public function setSelect(array $select) : static
    {
        $this->select = $select;
        return $this;
    }



    public function getFilters() : array
    {
        return $this->filters;
    }

    public function setFilters(array $filters) : static
    {
        $this->filters = $filters;
        return $this;
    }

    public function getIncludes() : array
    {
        return $this->includes;
    }

    public function setIncludes(array $includes) : static
    {
        $this->includes = $includes;
        return $this;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function setFields(array $fields) : static
    {
        $this->fields = $fields;
        return $this;
    }

    public function getSorts() : array
    {
        return $this->sorts;
    }

    public function setSorts(array $sorts) : static
    {
        $this->sorts = $sorts;
        return $this;
    }


    protected function filters() : array
    {
        return [];
    }

    protected function select():array
    {
        return [];
    }

    protected function fields() : array
    {
        return [];
    }

    protected function includes() : array
    {
        return [];
    }

    protected function sorts() : array
    {
        return [];
    }


    protected function queryBuilder(bool $isRequest = false) : QueryBuilder
    {
        /**
         * 如果是 不是当前请求调用 会出现 自动加载条件问题
         */
        $request      = $isRequest === true ? request() : (new Request());
        $queryBuilder = QueryBuilder::for($this->getModelClass(), $request);

        // TODO
        // 支持 设置为空
        // 支持外部设置
        // 支持重写
        $queryBuilder->allowedFilters($this->filters);
        if(filled($this->select)){
            $queryBuilder->select($this->select);
        }
        $queryBuilder->allowedFields($this->fields);
        $queryBuilder->allowedIncludes($this->includes);
        $queryBuilder->allowedSorts($this->sorts);

        return $queryBuilder;
    }


}
