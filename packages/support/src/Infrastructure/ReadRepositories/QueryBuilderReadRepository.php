<?php

namespace RedJasmine\Support\Infrastructure\ReadRepositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\ReadRepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @property Model $modelClass
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


    public function find(FindQuery $query) : ?Model
    {
        return $this->query($query->except($query->getPrimaryKey()))
                    ->where($query->getPrimaryKey(), $query->getKey())
                    ->firstOrFail();
    }

    public function modelQuery(?Query $query = null) : Builder
    {
        return static::$modelClass::query();
    }


    /**
     * @param  Query|null  $query
     *
     * @return QueryBuilder|\Illuminate\Database\Eloquent\Builder|Builder
     */
    public function query(?Query $query = null) : QueryBuilder|\Illuminate\Database\Eloquent\Builder|Builder
    {

        $queryBuilder = QueryBuilder::for($this->modelQuery($query), $this->buildRequest($query));

        // 根据允许的过滤器、字段、包含关系和排序字段配置QueryBuilder
        // 只有当相应的允许列表不为空时，才应用相应的限制
        if (method_exists($this, 'allowedFilters') && $allowedFilters = $this->allowedFilters($query)) {
            $queryBuilder->allowedFilters($allowedFilters);
        }
        if (method_exists($this, 'allowedFields') && $allowedFields = $this->allowedFields($query)) {
            $queryBuilder->allowedFields($allowedFields);
        }
        if (method_exists($this, 'allowedIncludes') && $allowedIncludes = $this->allowedIncludes($query)) {
            $queryBuilder->allowedIncludes($allowedIncludes);
        }
        if (method_exists($this, 'allowedSorts') && $allowedSorts = $this->allowedSorts($query)) {
            $queryBuilder->allowedSorts($allowedSorts);
        }

        // 调用查询回调函数，进一步自定义查询逻辑
        $this->queryCallbacks($queryBuilder);

        // 返回构建好地查询对象
        return $queryBuilder;
    }


    /**
     * 构建请求对象
     *
     * 根据提供的查询对象，构建并返回一个Request对象此方法首先创建一个新的Request对象，
     * 然后根据提供的查询对象（或一个新的空查询对象，如果没有提供），填充Request对象的各个属性
     * 它特别关注于处理查询参数，如包含、附加、排序、字段和过滤参数，根据配置文件中的设置
     *
     * @param  Query|null  $query  可选的查询对象，用于构建请求如果未提供，则创建一个新的空查询对象
     *
     * @return Request 返回构建好的Request对象
     */
    protected function buildRequest(?Query $query = null) : Request
    {

        // 创建一个新的Request对象
        $request = (new Request());

        // 确保查询对象存在，如果未提供，则创建一个新的空查询对象
        $query = $query ?? Query::from([]);

        // 从配置文件中获取参数名称
        $includeParameterName = config('query-builder.parameters.include', 'include');
        $appendParameterName  = config('query-builder.parameters.append', 'append');
        $sortParameterName    = config('query-builder.parameters.sort', 'sort');
        $fieldsParameterName  = config('query-builder.parameters.fields', 'fields');
        $filterParameterName  = config('query-builder.parameters.filter', 'filter');


        $queryFilters = $query->except($includeParameterName,
            $appendParameterName,
            $fieldsParameterName,
            $sortParameterName,
            'page', 'perPage');

        // 如果过滤参数名称已配置，处理查询参数以生成过滤条件
        if (filled($filterParameterName)) {
            // 排除特定的查询参数，准备过滤条件
            // 将过滤条件添加到请求对象中
            $request->offsetSet($filterParameterName, $queryFilters->toArray());
        } else {
            // 如果未配置过滤参数，直接用查询参数初始化请求对象
            $request->initialize($queryFilters->toArray());
        }
        // 将包含、附加、排序和字段参数添加到请求对象中
        $request->offsetSet($includeParameterName, $query->include);
        $request->offsetSet($appendParameterName, $query->append);
        $request->offsetSet($sortParameterName, $query->sort);
        $request->offsetSet($fieldsParameterName, $query->fields);

        // 返回构建好的Request对象
        return $request;
    }


    protected function queryCallbacks($query) : static
    {
        foreach ($this->queryCallbacks as $callback) {
            $callback($query);
        }
        return $this;
    }

    public function paginate(PaginateQuery $query) : LengthAwarePaginator|Paginator
    {

        $queryBuilder = $this->query($query)->defaultSort($this->defaultSort);

        return $query->isWithCount() ? $queryBuilder->paginate($query->perPage) : $queryBuilder->simplePaginate($query->perPage);
    }
}
