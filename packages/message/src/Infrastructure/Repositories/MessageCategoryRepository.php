<?php

declare(strict_types = 1);

namespace RedJasmine\Message\Infrastructure\Repositories;

use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;
use RedJasmine\Support\Infrastructure\Repositories\HasTree;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 消息分类仓库实现
 *
 * 基于Repository实现，提供消息分类实体的读写操作能力
 */
class MessageCategoryRepository extends Repository implements MessageCategoryRepositoryInterface
{
    use HasTree;

    protected static string $modelClass = MessageCategory::class;

    /**
     * 设置默认排序
     */
    protected mixed $defaultSort = 'sort';

    /**
     * 配置允许的过滤器
     */
    protected function allowedFilters($query = null): array
    {
        return [
            // 精确匹配
            AllowedFilter::exact('id'),
            AllowedFilter::exact('parent_id'),
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_system'),

            // 部分匹配
            AllowedFilter::partial('name'),
            AllowedFilter::partial('description'),

            // 使用模型作用域
            AllowedFilter::scope('enabled'),
            AllowedFilter::scope('root'), // 顶级分类
            AllowedFilter::scope('children'), // 子分类

            // 自定义回调
            AllowedFilter::callback('has_children', function ($query, $value) {
                if ($value) {
                    return $query->has('children');
                }
                return $query->doesntHave('children');
            }),

            AllowedFilter::callback('level', function ($query, $value) {
                // 按层级过滤，0为顶级
                if ($value == 0) {
                    return $query->whereNull('parent_id');
                }
                // 可以扩展支持多级分类
                return $query;
            }),
        ];
    }

    /**
     * 配置允许的排序字段
     */
    protected function allowedSorts($query = null): array
    {
        return [
            // 字段排序
            AllowedSort::field('id'),
            AllowedSort::field('name'),
            AllowedSort::field('sort'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),

            // 自定义排序
            AllowedSort::callback('children_count', function ($query, $descending) {
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->withCount('children')
                           ->orderBy('children_count', $orderDirection);
            }),

            AllowedSort::callback('messages_count', function ($query, $descending) {
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->withCount('messages')
                           ->orderBy('messages_count', $orderDirection);
            }),
        ];
    }

    /**
     * 配置允许包含的关联
     */
    protected function allowedIncludes($query = null): array
    {
        return [
            AllowedInclude::relationship('parent'),
            AllowedInclude::relationship('children'),
            AllowedInclude::relationship('messages'),
            AllowedInclude::count('children'),
            AllowedInclude::count('messages'),
        ];
    }
}
