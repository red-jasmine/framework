<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Repositories;

use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Support\Domain\Repositories\RepositoryInterface;

/**
 * 消息写操作仓库接口
 */
interface MessageRepositoryInterface extends RepositoryInterface
{
    /**
     * 根据接收人查找消息
     */
    public function findByReceiver(string $receiverId): ?Message;

    /**
     * 批量标记消息为已读
     */
    public function markAsRead(array $messageIds, string $readerId): int;

    /**
     * 批量归档消息
     */
    public function archiveMessages(array $messageIds): int;

    /**
     * 根据模板查找消息
     */
    public function findByTemplate(int $templateId): array;

    /**
     * 根据分类查找消息
     */
    public function findByCategory(int $categoryId): array;

    /**
     * 删除过期消息
     */
    public function deleteExpiredMessages(\DateTimeInterface $expiredBefore): int;

    /**
     * 删除阅后即焚消息
     */
    public function deleteBurnAfterReadMessages(array $messageIds): int;

    /**
     * 更新推送状态
     */
    public function updatePushStatus(string $messageId, string $status): bool;

    /**
     * 批量更新推送状态
     */
    public function batchUpdatePushStatus(array $messageIds, string $status): int;

    /**
     * 查找待推送的消息
     */
    public function findPendingPushMessages(int $limit = 100): array;

    /**
     * 查找需要重试推送的消息
     */
    public function findRetryPushMessages(int $limit = 100): array;

    /**
     * 根据业务线和接收人查找消息
     */
    public function findByBizAndReceiver(string $biz, string $receiverId): array;

    /**
     * 查找高优先级消息
     */
    public function findHighPriorityMessages(int $limit = 100): array;

    /**
     * 查找紧急消息
     */
    public function findUrgentMessages(int $limit = 100): array;
}
