<?php

namespace RedJasmine\Article\Infrastructure\Repositories;

use RedJasmine\Article\Domain\Models\ArticleTag;
use RedJasmine\Article\Domain\Repositories\ArticleTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 文章标签仓库实现
 *
 * 基于Repository实现，提供文章标签实体的读写操作能力
 */
class ArticleTagRepository extends Repository implements ArticleTagRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = ArticleTag::class;

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null) : array
    {
        return [
            AllowedFilter::exact('id'),
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
