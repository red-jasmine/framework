<?php

namespace RedJasmine\Article\Infrastructure\Repositories;

use RedJasmine\Article\Domain\Models\Article;
use RedJasmine\Article\Domain\Repositories\ArticleRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 文章仓库实现
 *
 * 基于Repository实现，提供文章实体的读写操作能力
 */
class ArticleRepository extends Repository implements ArticleRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Article::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('category_id'),
            AllowedFilter::partial('search', 'title'),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null) : array
    {
        return [
            AllowedSort::field('id'),
            AllowedSort::field('title'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : array
    {
        return ['extension', 'tags', 'category'];
    }
}
