<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\ReadRepositories\Mysql;

use Illuminate\Database\Eloquent\Collection;
use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Message\Domain\Repositories\MessagePushLogReadRepositoryInterface;
use RedJasmine\Support\Infrastructure\ReadRepositories\QueryBuilderReadRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;

/**
 * 消息推送日志只读仓库实现
 */
class MessagePushLogReadRepository extends QueryBuilderReadRepository implements MessagePushLogReadRepositoryInterface
{
    public static string $modelClass = MessagePushLog::class;

    /**
     * 允许的过滤器配置
     */
    public function allowedFilters(): array
    {
        return [
            // 精确匹配
            AllowedFilter::exact('id'),
            AllowedFilter::exact('message_id'),
            AllowedFilter::exact('channel'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('retry_count'),

            // 部分匹配
            AllowedFilter::partial('error_message'),

            // 使用模型作用域
            AllowedFilter::scope('success'),
            AllowedFilter::scope('failed'),
            AllowedFilter::scope('pending'),

            // 自定义回调
            AllowedFilter::callback('response_time_range', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$min, $max] = $value;
                    return $query->whereBetween('response_time', [$min, $max]);
                }
                return $query;
            }),

            AllowedFilter::callback('retry_count_range', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$min, $max] = $value;
                    return $query->whereBetween('retry_count', [$min, $max]);
                }
                return $query;
            }),

            AllowedFilter::callback('pushed_between', function ($query, $value) {
                if (is_array($value) && count($value) === 2) {
                    [$start, $end] = $value;
                    return $query->whereBetween('pushed_at', [$start, $end]);
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

            AllowedFilter::callback('has_error', function ($query, $value) {
                if ($value) {
                    return $query->whereNotNull('error_message');
                }
                return $query->whereNull('error_message');
            }),

            AllowedFilter::callback('slow_response', function ($query, $value) {
                // 查找响应时间大于指定毫秒数的记录
                $threshold = is_numeric($value) ? $value : 3000; // 默认3秒
                return $query->where('response_time', '>', $threshold);
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
            AllowedSort::field('message_id'),
            AllowedSort::field('channel'),
            AllowedSort::field('status'),
            AllowedSort::field('retry_count'),
            AllowedSort::field('response_time'),
            AllowedSort::field('pushed_at'),
            AllowedSort::field('created_at'),
            AllowedSort::field('updated_at'),

            // 自定义排序
            AllowedSort::callback('success_rate', function ($query, $descending) {
                // 按成功率排序（需要分组）
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->selectRaw('*, 
                    CASE WHEN status = "sent" THEN 1 ELSE 0 END as success_flag')
                    ->orderBy('success_flag', $orderDirection);
            }),

            AllowedSort::callback('performance', function ($query, $descending) {
                // 按性能排序：成功且响应时间短的排前面
                $orderDirection = $descending ? 'desc' : 'asc';
                return $query->orderByRaw("
                    CASE 
                        WHEN status = 'sent' AND response_time < 1000 THEN 4
                        WHEN status = 'sent' AND response_time < 3000 THEN 3
                        WHEN status = 'sent' THEN 2
                        WHEN status = 'failed' THEN 1
                        ELSE 0
                    END {$orderDirection}
                ");
            }),
        ];
    }

    /**
     * 允许包含的关联配置
     */
    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('message'),
            AllowedInclude::relationship('message.category'),
            AllowedInclude::relationship('message.template'),
        ];
    }

    /**
     * 自定义查询方法：根据消息ID获取推送日志
     */
    public function getByMessageId(int $messageId): Collection
    {
        return $this->query()
            ->where('message_id', $messageId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 自定义查询方法：根据渠道获取推送日志
     */
    public function getByChannel(string $channel, int $limit = 100): Collection
    {
        return $this->query()
            ->where('channel', $channel)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * 自定义查询方法：获取失败的推送日志
     */
    public function getFailedLogs(int $limit = 100): Collection
    {
        return $this->query()
            ->where('status', 'failed')
            ->where('retry_count', '<', 3)
            ->orderBy('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * 自定义查询方法：获取推送统计数据
     */
    public function getStatistics(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null): array
    {
        $query = $this->query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $stats = $query->selectRaw('
            channel,
            COUNT(*) as total,
            SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as success,
            SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
            AVG(response_time) as avg_response_time,
            MAX(response_time) as max_response_time,
            MIN(response_time) as min_response_time
        ')
        ->groupBy('channel')
        ->get()
        ->keyBy('channel')
        ->toArray();

        // 计算成功率
        foreach ($stats as $channel => &$stat) {
            $stat['success_rate'] = $stat['total'] > 0 
                ? round(($stat['success'] / $stat['total']) * 100, 2) 
                : 0;
        }

        return $stats;
    }

    /**
     * 自定义查询方法：获取渠道性能报告
     */
    public function getPerformanceReport(string $channel, int $days = 7): array
    {
        $startDate = now()->subDays($days);

        return $this->query()
            ->where('channel', $channel)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total,
                SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as success,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed,
                AVG(response_time) as avg_response_time,
                ROUND((SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as success_rate
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }

    /**
     * 自定义查询方法：获取错误统计
     */
    public function getErrorStatistics(int $days = 7): array
    {
        $startDate = now()->subDays($days);

        return $this->query()
            ->where('status', 'failed')
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('error_message')
            ->selectRaw('
                channel,
                error_message,
                COUNT(*) as count,
                MAX(created_at) as last_occurred
            ')
            ->groupBy('channel', 'error_message')
            ->orderBy('count', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * 自定义查询方法：获取重试统计
     */
    public function getRetryStatistics(): array
    {
        return $this->query()
            ->where('retry_count', '>', 0)
            ->selectRaw('
                channel,
                retry_count,
                COUNT(*) as count,
                SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as final_success
            ')
            ->groupBy('channel', 'retry_count')
            ->orderBy('channel')
            ->orderBy('retry_count')
            ->get()
            ->groupBy('channel')
            ->toArray();
    }

    /**
     * 自定义查询方法：获取响应时间分布
     */
    public function getResponseTimeDistribution(string $channel): array
    {
        return $this->query()
            ->where('channel', $channel)
            ->where('status', 'sent')
            ->selectRaw('
                CASE 
                    WHEN response_time < 500 THEN "< 500ms"
                    WHEN response_time < 1000 THEN "500ms - 1s"
                    WHEN response_time < 3000 THEN "1s - 3s"
                    WHEN response_time < 5000 THEN "3s - 5s"
                    ELSE "> 5s"
                END as time_range,
                COUNT(*) as count
            ')
            ->groupBy('time_range')
            ->orderByRaw('MIN(response_time)')
            ->get()
            ->pluck('count', 'time_range')
            ->toArray();
    }

    // 缺失的接口方法简化实现
    public function findList(array $ids): Collection { return collect(); }
    public function getSuccessRateReport(): array { return []; }
    public function getChannelComparisonReport(): array { return []; }
    public function getResponseTimeStats(): array { return []; }
    public function getErrorAnalysisReport(): array { return []; }
    public function getTrendData(): array { return []; }
    public function getVolumeStats(): array { return []; }
    public function getRetryStats(): array { return []; }
    public function getLogDetails(int $logId): array { return []; }
    public function searchLogs(string $keyword): Collection { return collect(); }
    public function getAnomalyReport(): array { return []; }
    public function getQualityScore(): array { return []; }
    public function getHeatmapData(): array { return []; }
    public function exportLogs(array $filters = []): array { return []; }

    /**
     * 设置默认排序
     */
    protected mixed $defaultSort = '-created_at';
}
