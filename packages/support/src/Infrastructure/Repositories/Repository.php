<?php

namespace RedJasmine\Support\Infrastructure\Repositories;

use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

/**
 * Repository 基础仓库实现
 *
 * 实现了BaseRepositoryInterface，合并了读写操作的功能。
 * 继承了EloquentRepository的写操作能力，同时集成了QueryBuilderReadRepository的读操作能力。
 * 提供了统一的数据访问接口，支持复杂查询、分页、过滤、排序等功能。
 *
 * @template TClass of Model
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @var $modelClass class-string<TClass>
     */
    protected static string $modelClass;

    /**
     * 获取模型类名
     *
     * @return class-string<TClass>
     */
    public static function getModelClass() : string
    {
        return self::$modelClass;
    }

    // ========================================
    // 写操作实现 (Write Operations)
    // ========================================

    /**
     * 根据ID查找记录
     */
    public function find(mixed $id) : ?Model
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::find($id);
    }

    /**
     * 根据ID查找记录并加锁
     */
    public function findLock($id) : ?Model
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::lockForUpdate()->find($id);
    }


    /**
     * 根据唯一编号查找记录
     *
     * @param  string  $no
     *
     * @return ?Model
     */
    public function findByNo(string $no)
    {
        return static::$modelClass::uniqueNo($no)->firstOrFail();
    }

    /**
     * @param  string  $no
     *
     * @return ?Model
     */
    public function findByNoLock(string $no)
    {
        return static::$modelClass::lockForUpdate()->uniqueNo($no)->firstOrFail();
    }


    /**
     * 存储模型实例到数据库
     *
     * @throws Throwable
     */
    public function store(Model $model) : Model
    {
        $model->push();
        return $model;
    }

    /**
     * 更新模型实例数据
     *
     * @throws Throwable
     */
    public function update(Model $model) : Model
    {
        $model->push();
        return $model;
    }

    /**
     * 从数据库删除模型实例
     */
    public function delete(Model $model) : bool
    {
        return $model->delete();
    }

    // ========================================
    // 读操作实现 (Read Operations)
    // ========================================


    /**
     * 查询回调函数数组
     */
    protected array $queryCallbacks = [];

    /**
     * 默认排序规则
     */
    protected mixed $defaultSort = '-id';

    /**
     * 获取模型查询构建器
     */
    public function query() : Builder
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$modelClass;
        return $modelClass::query();
    }

    /**
     * 基于  laravel-query-builder 的查询构建器
     *
     * @param  Query|null  $query
     *
     * @return QueryBuilder
     */
    public function queryBuilder(?Query $query = null) : QueryBuilder
    {
        $queryBuilder = QueryBuilder::for($this->query(), $this->buildRequest($query));

        // 根据允许的过滤器、字段、包含关系和排序字段配置QueryBuilder
        $queryBuilder->allowedFilters($this->allowedFilters($query));
        $queryBuilder->allowedFields($this->allowedFields($query));
        $queryBuilder->allowedIncludes($this->allowedIncludes($query));
        $queryBuilder->allowedSorts($this->allowedSorts($query));
        $queryBuilder->defaultSort($this->defaultSort);

        // 调用查询回调函数，进一步自定义查询逻辑
        $this->queryCallbacks($queryBuilder);

        return $queryBuilder;
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters(?Query $query = null) : array
    {
        return [];
    }

    /**
     * 配置允许的字段
     */
    protected function allowedFields(?Query $query = null) : array
    {
        return [];
    }

    /**
     * 配置允许的包含关系
     */
    protected function allowedIncludes(?Query $query = null) : array
    {
        return [];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts(?Query $query = null) : array
    {
        return [];
    }

    /**
     * 添加自定义查询回调函数
     */
    public function withQuery(Closure $queryCallback) : static
    {
        $this->queryCallbacks[] = $queryCallback;
        return $this;
    }

    /**
     * 根据查询条件查找单个记录
     */
    public function findByQuery(FindQuery $query) : ?Model
    {
        $queryBuilder = $this->queryBuilder($query->except($query->getPrimaryKey()));

        // QueryBuilder 和 Builder 都有 where 方法
        return $queryBuilder->where(Str::snake($query->getPrimaryKey()), $query->getKey())->first();
    }

    /**
     * 分页查询记录列表
     */
    public function paginate(PaginateQuery $query) : LengthAwarePaginator|Paginator
    {
        $queryBuilder = $this->queryBuilder($query);

        return $query->isWithCount()
            ? $queryBuilder->paginate($query->perPage)
            : $queryBuilder->simplePaginate($query->perPage);
    }

    // ========================================
    // 辅助方法 (Helper Methods)
    // ========================================

    /**
     * 构建请求对象
     *
     * 根据提供的查询对象，构建并返回一个Request对象
     */
    protected function buildRequest(?Query $query = null) : Request
    {
        $request = new Request();
        $query   = $query ?? Query::from([]);

        // 从配置文件中获取参数名称
        $includeParameterName = config('query-builder.parameters.include', 'include');
        $appendParameterName  = config('query-builder.parameters.append', 'append');
        $sortParameterName    = config('query-builder.parameters.sort', 'sort');
        $fieldsParameterName  = config('query-builder.parameters.fields', 'fields');
        $filterParameterName  = config('query-builder.parameters.filter', 'filter');

        $queryFilters = $query->except(
            $includeParameterName,
            $appendParameterName,
            $fieldsParameterName,
            $sortParameterName,
            'page',
            'perPage'
        );

        // 处理过滤参数
        if (filled($filterParameterName)) {
            $request->offsetSet($filterParameterName, $queryFilters->toArray());
        } else {
            $request->initialize($queryFilters->toArray());
        }

        // 设置其他参数
        $request->offsetSet($includeParameterName, $query->include);
        $request->offsetSet($appendParameterName, $query->append);
        $request->offsetSet($sortParameterName, $query->sort);
        $request->offsetSet($fieldsParameterName, $query->fields);

        return $request;
    }

    /**
     * 执行查询回调函数
     */
    protected function queryCallbacks($query) : static
    {
        foreach ($this->queryCallbacks as $callback) {
            $callback($query);
        }
        return $this;
    }


}
