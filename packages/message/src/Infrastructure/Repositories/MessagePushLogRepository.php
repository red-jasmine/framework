<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\Repositories;

use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Message\Domain\Repositories\MessagePushLogRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Repository;

/**
 * 消息推送日志仓库实现
 */
class MessagePushLogRepository extends Repository implements MessagePushLogRepositoryInterface
{
    protected static string $modelClass = MessagePushLog::class;

    // 基础接口方法实现
    public function findByMessage(int $messageId): array
    {
        return MessagePushLog::where('message_id', $messageId)->get()->toArray();
    }

    public function findByMessageAndChannel(int $messageId, string $channel): ?MessagePushLog
    {
        return MessagePushLog::where('message_id', $messageId)
            ->where('channel', $channel)
            ->first();
    }

    public function findRetryableLogs(int $limit = 100): array
    {
        return MessagePushLog::where('status', 'failed')
            ->where('retry_count', '<', 3)
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function batchIncrementRetryCount(array $ids): int
    {
        return MessagePushLog::whereIn('id', $ids)->increment('retry_count');
    }

    public function cleanOldLogs(\DateTimeInterface $before): int
    {
        return MessagePushLog::where('created_at', '<', $before)->delete();
    }

    public function findByExternalId(string $externalId): ?MessagePushLog
    {
        return MessagePushLog::where('external_id', $externalId)->first();
    }

    public function getSuccessRateStats(string $channel): array
    {
        $total = MessagePushLog::where('channel', $channel)->count();
        $success = MessagePushLog::where('channel', $channel)->where('status', 'sent')->count();
        return [
            'total' => $total,
            'success' => $success,
            'rate' => $total > 0 ? round(($success / $total) * 100, 2) : 0
        ];
    }

    public function getChannelStats(): array
    {
        return MessagePushLog::selectRaw('channel, COUNT(*) as total,
                                        SUM(CASE WHEN status = "sent" THEN 1 ELSE 0 END) as success')
            ->groupBy('channel')
            ->get()
            ->toArray();
    }

    public function getErrorStats(): array
    {
        return MessagePushLog::where('status', 'failed')
            ->selectRaw('error_message, COUNT(*) as count')
            ->groupBy('error_message')
            ->get()
            ->toArray();
    }

    public function findTimeoutLogs(int $timeoutSeconds = 30): array
    {
        return MessagePushLog::where('response_time', '>', $timeoutSeconds * 1000)
            ->get()
            ->toArray();
    }

    public function updatePushResult(int $id, array $result): bool
    {
        return MessagePushLog::where('id', $id)->update($result) > 0;
    }
}
