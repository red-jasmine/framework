<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use Closure;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property  Model $modelClass
 */
abstract class QueryBuilderReadRepository implements ReadRepositoryInterface
{
    protected ?array $allowedFilters  = null;
    protected ?array $allowedIncludes = null;
    protected ?array $allowedFields   = null;
    protected ?array $allowedSorts    = null;
    /**
     * @var array
     */
    protected array $queryCallbacks = [];
    protected mixed $defaultSort    = '-id';

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

    public function setQueryCallbacks(array $queryCallbacks) : static
    {
        $this->queryCallbacks = $queryCallbacks;
        return $this;
    }

    /**
     * 添加查询回调函数
     *
     * 该方法用于向某个实例中添加一个查询回调函数。查询回调函数通常是在数据查询执行后进行一些特定操作的回调函数。
     * 此方法通过返回当前实例，支持链式调用，以方便在一行代码中添加多个查询回调。
     *
     * @param  Closure  $queryCallback  要添加的查询回调函数。该回调函数应接受当前实例作为参数。
     *
     * @return static 返回当前实例，支持链式调用。
     */
    public function withQuery(Closure $queryCallback) : static
    {
        $this->queryCallbacks[] = $queryCallback;
        return $this;
    }


    public function getModelQuery() : \Illuminate\Database\Eloquent\Builder
    {
        return static::$modelClass::query();
    }

    /**
     * 根据请求查询参数构建查询对象
     *
     * 此方法用于初始化QueryBuilder对象，该对象用于构造和执行数据库查询此方法允许通过请求查询参数
     * 自定义查询，同时确保仅允许使用预定义的过滤器、字段、排序和包含关系
     *
     * @param  array  $requestQuery  请求中的查询参数，默认为空数组如果未提供，则使用空数组
     *
     * @return QueryBuilder 返回构建好地查询对象，以便进一步操作或执行查询
     */
    protected function query(array $requestQuery = []) : QueryBuilder
    {
        // 初始化QueryBuilder对象，使用模型类和请求查询参数进行构建
        $queryBuilder = QueryBuilder::for($this->getModelQuery(), $this->buildRequest($requestQuery));
        // 设置默认排序方式
        $queryBuilder->defaultSort($this->defaultSort);

        // 根据允许的过滤器、字段、包含关系和排序字段配置QueryBuilder
        // 只有当相应的允许列表不为空时，才应用相应的限制
        $this->allowedFilters ? $queryBuilder->allowedFilters($this->allowedFilters) : null;
        $this->allowedFields ? $queryBuilder->allowedFields($this->allowedFields) : null;
        $this->allowedIncludes ? $queryBuilder->allowedIncludes($this->allowedIncludes) : null;
        $this->allowedSorts ? $queryBuilder->allowedSorts($this->allowedSorts) : null;

        // 调用查询回调函数，进一步自定义查询逻辑
        $this->queryCallbacks($queryBuilder);

        // 返回构建好地查询对象
        return $queryBuilder;
    }


    /**
     * 构建请求对象
     *
     * 本方法用于根据传入的查询参数数组构建一个请求对象。它会从配置文件中读取一系列
     * 查询参数配置项，并根据这些配置项对传入的查询参数进行处理，最终生成一个初始化的
     * Request 对象
     *
     * @param  array  $requestQuery  查询参数数组，默认为空数组。这允许在构建请求时预设一些查询参数
     *
     * @return Request 返回一个初始化并设置了查询参数的 Request 对象
     */
    protected function buildRequest(array $requestQuery = []) : Request
    {
        // 从配置文件中获取参数名称
        $includeParameterName = config('query-builder.parameters.include', 'include');
        $appendParameterName  = config('query-builder.parameters.append', 'append');
        $fieldsParameterName  = config('query-builder.parameters.fields', 'fields');
        $sortParameterName    = config('query-builder.parameters.sort', 'sort');
        $filterParameterName  = config('query-builder.parameters.filter', 'filter');

        // 如果filter参数存在，则移除某些默认参数，以避免冲突或不必要的处理
        if (filled($filterParameterName)) {
            $requestQuery[$filterParameterName] = Arr::except($requestQuery, [
                'include', 'append', 'fields', 'append', 'sort', 'page', 'per_page'
            ]);
        }

        // 创建一个新的Request对象，并用处理后的查询参数初始化它
        $request = (new Request());
        $request->initialize($requestQuery);

        return $request;
    }

    protected function queryCallbacks($query) : static
    {
        foreach ($this->queryCallbacks as $callback) {
            $callback($query);
        }
        return $this;
    }


    public function find(FindQuery $query) : ?Model
    {
        $id = $query->id;
        return $this->query($query->except('id')->toArray() ?? [])->findOrFail($id);
    }


    public function paginate(?PaginateQuery $query = null) : LengthAwarePaginator
    {
        return $this->query($query?->toArray())->paginate($query?->perPage);
    }

    public function simplePaginate(?PaginateQuery $query = null) : Paginator
    {
        return $this->query($query?->toArray())->simplePaginate($query?->perPage);
    }
}
