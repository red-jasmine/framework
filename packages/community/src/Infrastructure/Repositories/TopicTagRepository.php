<?php

namespace RedJasmine\Community\Infrastructure\Repositories;

use RedJasmine\Community\Domain\Models\TopicTag;
use RedJasmine\Community\Domain\Repositories\TopicTagRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 话题标签仓库实现
 *
 * 基于Repository实现，提供话题标签实体的读写操作能力
 */
class TopicTagRepository extends Repository implements TopicTagRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = TopicTag::class;

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
