<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryReadRepositoryInterface;
use RedJasmine\Support\Domain\Data\Queries\Query;
use RedJasmine\Support\Infrastructure\ReadRepositories\HasTree;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 消息分类只读仓库实现
 */
class MessageCategoryReadRepository extends QueryBuilderReadRepository implements MessageCategoryReadRepositoryInterface
{
    public static string $modelClass = MessageCategory::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
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
     * 允许的排序字段配置
     */
    public function allowedSorts(): array
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
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('parent'),
            AllowedInclude::relationship('children'),
            AllowedInclude::relationship('messages'),
            AllowedInclude::count('children'),
            AllowedInclude::count('messages'),
        ];
    }

    use HasTree;


    /**
     * 设置默认排序
     */
    protected mixed $defaultSort = 'sort';
}
