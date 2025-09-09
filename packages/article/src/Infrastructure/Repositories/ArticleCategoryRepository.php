<?php

namespace RedJasmine\Article\Infrastructure\Repositories;

use RedJasmine\Article\Domain\Models\ArticleCategory;
use RedJasmine\Article\Domain\Repositories\ArticleCategoryRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 文章分类仓库实现
 *
 * 基于Repository实现，提供文章分类实体的读写操作能力
 */
class ArticleCategoryRepository extends Repository implements ArticleCategoryRepositoryInterface
{
    use HasTree;

    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ArticleCategory::class;

    /**
     * 根据名称查找分类
     */
    public function findByName($name) : ?ArticleCategory
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
