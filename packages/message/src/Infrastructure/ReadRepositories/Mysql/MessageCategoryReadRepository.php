<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Message\Domain\Models\MessageCategory;
use RedJasmine\Message\Domain\Repositories\MessageCategoryReadRepositoryInterface;
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

    /**
     * 自定义查询方法：获取分类树
     */
    public function getTree(?int $parentId = null): Collection
    {
        return $this->query()
            ->where('parent_id', $parentId)
            ->where('status', 'enable')
            ->with(['children' => function ($query) {
                $query->where('status', 'enable')->orderBy('sort');
            }])
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：获取启用的分类列表
     */
    public function getEnabledList(): Collection
    {
        return $this->query()
            ->where('status', 'enable')
            ->orderBy('sort')
            ->orderBy('name')
            ->get();
    }

    /**
     * 自定义查询方法：根据业务线获取分类
     */
    public function getByBiz(string $biz): Collection
    {
        return $this->query()
            ->where('biz', $biz)
            ->where('status', 'enable')
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：获取顶级分类
     */
    public function getRootCategories(): Collection
    {
        return $this->query()
            ->whereNull('parent_id')
            ->where('status', 'enable')
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：获取子分类
     */
    public function getChildren(int $parentId): Collection
    {
        return $this->query()
            ->where('parent_id', $parentId)
            ->where('status', 'enable')
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：获取分类路径
     */
    public function getCategoryPath(int $categoryId): array
    {
        $path = [];
        $category = $this->query()->find($categoryId);
        
        while ($category) {
            array_unshift($path, [
                'id' => $category->id,
                'name' => $category->name,
            ]);
            
            $category = $category->parent_id ? 
                $this->query()->find($category->parent_id) : null;
        }
        
        return $path;
    }

    /**
     * 自定义查询方法：搜索分类
     */
    public function search(string $keyword): Collection
    {
        return $this->query()
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->where('status', 'enable')
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：获取使用统计
     */
    public function getUsageStatistics(): Collection
    {
        return $this->query()
            ->withCount('messages')
            ->orderBy('messages_count', 'desc')
            ->get();
    }

    // 缺失的接口方法简化实现
    public function findList(array $ids): Collection { return collect(); }
    public function getStatistics(): array { return []; }
    public function searchCategories(string $keyword): Collection { return collect(); }
    public function getCategoryRanking(): array { return []; }
    public function getMessageCountStats(): array { return []; }
    public function getActivityStats(): array { return []; }
    public function getGroupedByBiz(): array { return []; }
    public function getRecentlyUsed(int $limit = 10): Collection { return collect(); }
    public function getPopularCategories(int $limit = 10): Collection { return collect(); }
    public function getCategoryDetailStats(int $categoryId): array { return []; }

    /**
     * 设置默认排序
     */
    protected mixed $defaultSort = 'sort';
}
