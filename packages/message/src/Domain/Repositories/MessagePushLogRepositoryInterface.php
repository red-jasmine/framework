<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息推送日志写操作仓库接口
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
}
