<?php

namespace RedJasmine\Community\Infrastructure\Repositories;

use RedJasmine\Community\Domain\Models\TopicCategory;
use RedJasmine\Community\Domain\Repositories\TopicCategoryRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 话题分类仓库实现
 *
 * 基于Repository实现，提供话题分类实体的读写操作能力
 */
class TopicCategoryRepository extends Repository implements TopicCategoryRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = TopicCategory::class;

    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?TopicCategory
    {
        return static::$modelClass::where('name', $name)->first();
    }

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::partial('name'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('name'),
            AllowedSort::field('sort'),
            AllowedSort::field('created_at'),
        ];
    }
}
