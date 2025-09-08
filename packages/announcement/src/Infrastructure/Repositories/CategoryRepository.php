<?php

namespace RedJasmine\Announcement\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use RedJasmine\Announcement\Domain\Models\AnnouncementCategory;
use RedJasmine\Announcement\Domain\Repositories\CategoryRepositoryInterface;
use RedJasmine\Support\Data\UserData;
use RedJasmine\Support\Domain\Data\Queries\FindQuery;
use RedJasmine\Support\Domain\Data\Queries\PaginateQuery;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

class CategoryRepository extends Repository implements CategoryRepositoryInterface
{
    protected static string $modelClass = AnnouncementCategory::class;


    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?AnnouncementCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }

    use HasTree;


    /**
     * 根据查询条件查找单个分类
     */
    public function findByQuery(FindQuery $query) : ?AnnouncementCategory
    {
        $builder = $this->queryBuilder($query);
        return $builder->first();
    }

    /**
     * 分页查询分类
     */
    public function paginate(PaginateQuery $query) : LengthAwarePaginator
    {
        $builder = $this->queryBuilder($query);
        return $builder->paginate($query->perPage ?? 15);
    }

    /**
     * 设置查询作用域
     */
    public function withQuery(\Closure $closure) : static
    {
        $this->queryCallback = $closure;
        return $this;
    }

    /**
     * 允许包含的关联配置
     *
     * @param  Query|null  $query
     *
     * @return array
     */
    protected function allowedIncludes(?Query $query = null) : array
    {
        return [
            'parent',
            'children',
            'announcements',
        ];
    }

    /**
     * @param  Query|null  $query
     *
     * @return string[]
     */
    protected function allowedSorts(?Query $query = null) : array
    {
        return [
            'id',
            'name',
            'sort',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * 允许的过滤器配置
     *
     * @param  Query|null  $query
     *
     * @return array
     */
    protected function allowedFilters(?Query $query = null) : array
    {
        return [
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('is_show'),
            AllowedFilter::partial('name'),
            AllowedFilter::callback('owner', fn(Builder $builder, $value) => $builder->onlyOwner(
                is_array($value) ? UserData::from($value) : $value
            )),
        ];
    }
}
