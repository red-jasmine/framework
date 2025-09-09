<?php

namespace RedJasmine\Community\Infrastructure\Repositories;

use RedJasmine\Community\Domain\Models\Topic;
use RedJasmine\Community\Domain\Repositories\TopicRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * 话题仓库实现
 *
 * 基于Repository实现，提供话题实体的读写操作能力
 */
class TopicRepository extends Repository implements TopicRepositoryInterface
{
    /**
     * @var string Eloquent模型类
     */
    protected static string $modelClass = Topic::class;

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
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null) : array
    {
        return ['extension', 'category'];
    }
}
