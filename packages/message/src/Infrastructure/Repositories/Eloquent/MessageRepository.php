<?php

declare(strict_types=1);

namespace RedJasmine\Message\Infrastructure\Repositories\Eloquent;

use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Repositories\MessageRepositoryInterface;
use RedJasmine\Support\Infrastructure\Repositories\Eloquent\EloquentRepository;

/**
 * 消息仓库实现
 */
class MessageRepository extends EloquentRepository implements MessageRepositoryInterface
{
    protected static string $eloquentModelClass = Message::class;

    /**
     * 根据接收人查找消息
     */
    public function findByReceiver(string $receiverId): ?Message
    {
        return Message::where('receiver_id', $receiverId)->first();
    }

    /**
     * 批量标记消息为已读
     */
    public function markAsRead(array $messageIds, string $readerId): int
    {
        return Message::whereIn('id', $messageIds)
            ->where('receiver_id', $readerId)
            ->update(['status' => 'read', 'read_at' => now()]);
    }

    /**
     * 批量归档消息
     */
    public function archiveMessages(array $messageIds): int
    {
        return Message::whereIn('id', $messageIds)
            ->update(['status' => 'archived']);
    }

    /**
     * 根据模板ID查找消息
     */
    public function findByTemplate(int $templateId): array
    {
        return Message::where('template_id', $templateId)
            ->get()
            ->toArray();
    }

    /**
     * 根据分类查找消息
     */
    public function findByCategory(int $categoryId): array
    {
        return Message::where('category_id', $categoryId)
            ->get()
            ->toArray();
    }

    /**
     * 删除过期消息
     */
    public function deleteExpiredMessages(\DateTimeInterface $expiredBefore): int
    {
        return Message::whereNotNull('expires_at')
            ->where('expires_at', '<', $expiredBefore)
            ->delete();
    }

    /**
     * 删除阅后即焚消息
     */
    public function deleteBurnAfterReadMessages(array $messageIds): int
    {
        return Message::whereIn('id', $messageIds)
            ->where('is_burn_after_read', true)
            ->delete();
    }

    /**
     * 更新推送状态
     */
    public function updatePushStatus(string $messageId, string $status): bool
    {
        return Message::where('id', $messageId)
            ->update(['push_status' => $status]) > 0;
    }

    /**
     * 批量更新推送状态
     */
    public function batchUpdatePushStatus(array $messageIds, string $status): int
    {
        return Message::whereIn('id', $messageIds)
            ->update(['push_status' => $status]);
    }

    /**
     * 查找待推送的消息
     */
    public function findPendingPushMessages(int $limit = 100): array
    {
        return Message::where('push_status', 'pending')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 查找需要重试推送的消息
     */
    public function findRetryPushMessages(int $limit = 100): array
    {
        return Message::where('push_status', 'failed')
            ->whereRaw('JSON_EXTRACT(data, "$.retry_count") < 3')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 根据业务线和接收人查找消息
     */
    public function findByBizAndReceiver(string $biz, string $receiverId): array
    {
        return Message::where('biz', $biz)
            ->where('receiver_id', $receiverId)
            ->get()
            ->toArray();
    }

    /**
     * 查找高优先级消息
     */
    public function findHighPriorityMessages(int $limit = 100): array
    {
        return Message::whereIn('priority', ['high', 'urgent'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * 查找紧急消息
     */
    public function findUrgentMessages(int $limit = 100): array
    {
        return Message::where(function ($query) {
                $query->where('priority', 'urgent')
                      ->orWhere('is_urgent', true);
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
