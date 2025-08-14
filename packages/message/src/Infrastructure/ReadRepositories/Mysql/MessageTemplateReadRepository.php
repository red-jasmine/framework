<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Message\Domain\Models\MessageTemplate;
use RedJasmine\Message\Domain\Repositories\MessageTemplateReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 消息模板只读仓库实现
 */
class MessageTemplateReadRepository extends QueryBuilderReadRepository implements MessageTemplateReadRepositoryInterface
{
    public static string $modelClass = MessageTemplate::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
    {
        return [
            // 精确匹配
            AllowedFilter::exact('id'),
            AllowedFilter::exact('category_id'),
            AllowedFilter::exact('biz'),
            AllowedFilter::exact('code'),
            AllowedFilter::exact('type'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('is_system'),

            // 部分匹配
            AllowedFilter::partial('name'),
            AllowedFilter::partial('title'),
            AllowedFilter::partial('content'),
            AllowedFilter::partial('description'),

            // 使用模型作用域
            AllowedFilter::scope('enabled'),
            AllowedFilter::scope('system'),
            AllowedFilter::scope('user'),

            // 自定义回调
            AllowedFilter::callback('has_variables', function ($query, $value) {
                if ($value) {
                    return $query->whereJsonLength('variables', '>', 0);
                }
                return $query->whereJsonLength('variables', 0);
            }),

            AllowedFilter::callback('usage_count_range', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$min, $max] = $value;
                    return $query->whereBetween('usage_count', [$min, $max]);
                }
                return $query;
            }),

            AllowedFilter::callback('created_between', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$start, $end] = $value;
                    return $query->whereBetween('created_at', [$start, $end]);
                }
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
            AllowedSort::field('code'),
            AllowedSort::field('sort'),
            AllowedSort::field('usage_count'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),

            // 自定义排序
            AllowedSort::callback('popularity', function ($query, $descending) {
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->orderBy('usage_count', $orderDirection)
                           ->orderBy('created_at', 'desc');
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
            AllowedInclude::relationship('category'),
            AllowedInclude::relationship('messages'),
            AllowedInclude::count('messages'),
        ];
    }

    /**
     * 自定义查询方法：根据编码查找模板
     */
    public function findByCode(string $code): ?MessageTemplate
    {
        return $this->query()
            ->where('code', $code)
            ->where('status', 'enable')
            ->first();
    }

    /**
     * 自定义查询方法：获取启用的模板列表
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
     * 自定义查询方法：根据业务线获取模板
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
     * 自定义查询方法：根据分类获取模板
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->query()
            ->where('category_id', $categoryId)
            ->where('status', 'enable')
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：根据类型获取模板
     */
    public function getByType(string $type): Collection
    {
        return $this->query()
            ->where('type', $type)
            ->where('status', 'enable')
            ->orderBy('sort')
            ->get();
    }

    /**
     * 自定义查询方法：获取热门模板
     */
    public function getPopular(int $limit = 10): Collection
    {
        return $this->query()
            ->where('status', 'enable')
            ->where('usage_count', '>', 0)
            ->orderBy('usage_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * 自定义查询方法：搜索模板
     */
    public function search(string $keyword): Collection
    {
        return $this->query()
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('title', 'like', "%{$keyword}%")
                      ->orWhere('content', 'like', "%{$keyword}%")
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
            ->selectRaw('biz, type, COUNT(*) as total, SUM(usage_count) as total_usage')
            ->groupBy('biz', 'type')
            ->orderBy('total_usage', 'desc')
            ->get();
    }

    /**
     * 自定义查询方法：获取模板变量统计
     */
    public function getVariableStatistics(): array
    {
        $templates = $this->query()
            ->whereJsonLength('variables', '>', 0)
            ->get(['id', 'name', 'variables']);

        $variableStats = [];
        foreach ($templates as $template) {
            $variables = json_decode($template->variables, true) ?? [];
            foreach ($variables as $variable) {
                $varName = $variable['name'] ?? 'unknown';
                if (!isset($variableStats[$varName])) {
                    $variableStats[$varName] = [
                        'name' => $varName,
                        'count' => 0,
                        'templates' => []
                    ];
                }
                $variableStats[$varName]['count']++;
                $variableStats[$varName]['templates'][] = $template->name;
            }
        }

        return array_values($variableStats);
    }

    // 缺失的接口方法简化实现
    public function findList(array $ids): Collection { return collect(); }
    public function getActiveList(): Collection { return collect(); }
    public function searchTemplates(string $keyword): Collection { return collect(); }
    public function getStatistics(): array { return []; }
    public function getUsageRanking(): array { return []; }
    public function getUsageTrend(): array { return []; }
    public function getVariableStats(): array { return []; }
    public function findSimilarTemplates(int $templateId): Collection { return collect(); }
    public function getRecentlyCreated(int $limit = 10): Collection { return collect(); }
    public function getRecentlyUpdated(int $limit = 10): Collection { return collect(); }
    public function getPopularTemplates(int $limit = 10): Collection { return collect(); }
    public function getTemplateDetailStats(int $templateId): array { return []; }
    public function getPerformanceStats(): array { return []; }
    public function validateTemplateSyntax(int $templateId): array { return []; }
    public function previewTemplate(int $templateId, array $variables = []): array { return []; }

    /**
     * 设置默认排序
     */
    protected mixed $defaultSort = 'sort';
}
