<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property Model $modelClass
 */
abstract class QueryBuilderReadRepository
{
    protected ?array $allowedFilters  = null;
    protected ?array $allowedIncludes = null;
    protected ?array $allowedFields   = null;
    protected ?array $allowedSorts    = null;

    public function setAllowedFilters(?array $allowedFilters) : static
    {
        $this->allowedFilters = $allowedFilters;
        return $this;
    }

    public function setAllowedIncludes(?array $allowedIncludes) : static
    {
        $this->allowedIncludes = $allowedIncludes;
        return $this;
    }

    public function setAllowedFields(?array $allowedFields) : static
    {
        $this->allowedFields = $allowedFields;
        return $this;
    }

    public function setAllowedSorts(?array $allowedSorts) : static
    {
        $this->allowedSorts = $allowedSorts;
        return $this;
    }


    /**
     * @var array
     */
    protected array $queryCallbacks = [];

    public function setQueryCallbacks(array $queryCallbacks) : static
    {
        $this->queryCallbacks = $queryCallbacks;
        return $this;
    }

    protected function queryCallbacks($query) : static
    {
        foreach ($this->queryCallbacks as $callback) {
            $callback($query);
        }
        return $this;
    }


    protected mixed $defaultSort = '-id';

    protected function query(array $query = []) : QueryBuilder
    {
        $request = (new Request());
        $request->initialize($query);
        $query = QueryBuilder::for($this->modelClass::query(), $request);
        $query->defaultSort($this->defaultSort);
        $this->allowedFilters ? $query->allowedFilters($this->allowedFilters) : null;
        $this->allowedFields ? $query->allowedFields($this->allowedFields) : null;
        $this->allowedIncludes ? $query->allowedIncludes($this->allowedIncludes) : null;
        $this->allowedSorts ? $query->allowedSorts($this->allowedSorts) : null;
        $this->queryCallbacks($query);
        return $query;
    }


}
