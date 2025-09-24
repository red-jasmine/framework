<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息推送日志仓库接口
 *
 * 提供消息推送日志实体的读写操作统一接口
 */
interface MessagePushLogRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据消息ID查找推送日志
     */
    public function findByMessage(string $messageId): array;

    /**
     * 根据消息ID和渠道查找推送日志
     */
    public function findByMessageAndChannel(string $messageId, string $channel): ?MessagePushLog;

    /**
     * 查找失败的推送日志
     */
    public function findFailedLogs(int $limit = 100): array;

    /**
     * 查找可重试的推送日志
     */
    public function findRetryableLogs(int $maxRetries = 3, int $limit = 100): array;

    /**
     * 批量更新推送状态
     */
    public function batchUpdateStatus(array $logIds, string $status): int;

    /**
     * 增加重试次数
     */
    public function incrementRetryCount(int $logId): bool;

    /**
     * 批量增加重试次数
     */
    public function batchIncrementRetryCount(array $logIds): int;

    /**
     * 清理旧的推送日志
     */
    public function cleanOldLogs(\DateTimeInterface $before): int;

    /**
     * 根据外部ID查找推送日志
     */
    public function findByExternalId(string $externalId): ?MessagePushLog;

    /**
     * 获取推送成功率统计
     */
    public function getSuccessRateStats(array $filters = []): array;

    /**
     * 获取各渠道推送统计
     */
    public function getChannelStats(array $filters = []): array;

    /**
     * 获取推送错误统计
     */
    public function getErrorStats(array $filters = []): array;

    /**
     * 查找超时的推送日志
     */
    public function findTimeoutLogs(int $timeoutMinutes = 30): array;

    /**
     * 更新推送结果
     */
    public function updatePushResult(int $logId, array $result): bool;


    /**
     * 根据ID列表查找推送日志
     */
    public function findList(array $ids): \Illuminate\Support\Collection;

    /**
     * 获取推送统计数据
     */
    public function getStatistics(array $filters = []): array;

    /**
     * 获取推送成功率报告
     */
    public function getSuccessRateReport(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 获取各渠道推送效果对比
     */
    public function getChannelComparisonReport(array $filters = []): array;

    /**
     * 获取推送响应时间统计
     */
    public function getResponseTimeStats(array $filters = []): array;

    /**
     * 获取推送错误分析报告
     */
    public function getErrorAnalysisReport(array $filters = []): array;

    /**
     * 获取推送趋势数据
     */
    public function getTrendData(\DateTimeInterface $start, \DateTimeInterface $end, string $groupBy = 'day'): array;

    /**
     * 获取推送量统计
     */
    public function getVolumeStats(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 获取重试统计
     */
    public function getRetryStats(array $filters = []): array;

    /**
     * 获取推送日志详细信息
     */
    public function getLogDetails(int $logId): ?array;

    /**
     * 搜索推送日志
     */
    public function searchLogs(string $keyword, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * 获取推送性能报告
     */
    public function getPerformanceReport(array $filters = []): array;

    /**
     * 获取推送异常报告
     */
    public function getAnomalyReport(array $filters = []): array;

    /**
     * 获取推送质量评分
     */
    public function getQualityScore(array $filters = []): float;

    /**
     * 获取推送热力图数据
     */
    public function getHeatmapData(\DateTimeInterface $start, \DateTimeInterface $end): array;

    /**
     * 导出推送日志数据
     */
    public function exportLogs(array $filters = [], string $format = 'csv'): string;
}
