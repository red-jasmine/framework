<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\Message;

/**
 * 消息归档事件
 */
class MessageArchived
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Message $message
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.archived';
    }

    /**
     * 获取消息ID
     */
    public function getMessageId(): string
    {
        return $this->message->id;
    }

    /**
     * 获取接收人ID
     */
    public function getReceiverId(): string
    {
        return $this->message->receiver_id;
    }

    /**
     * 获取业务线
     */
    public function getBiz(): string
    {
        return $this->message->biz->value;
    }

    /**
     * 获取消息类型
     */
    public function getMessageType(): string
    {
        return $this->message->type->value;
    }

    /**
     * 获取消息分类ID
     */
    public function getCategoryId(): ?int
    {
        return $this->message->category_id;
    }

    /**
     * 是否已读
     */
    public function wasRead(): bool
    {
        return $this->message->read_at !== null;
    }

    /**
     * 获取消息存活时间
     */
    public function getMessageLifetime(): int
    {
        if (!$this->message->created_at) {
            return 0;
        }

        return now()->getTimestamp() - $this->message->created_at->getTimestamp();
    }

    /**
     * 转换为数组
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->getEventId(),
            'message_id' => $this->getMessageId(),
            'receiver_id' => $this->getReceiverId(),
            'biz' => $this->getBiz(),
            'category_id' => $this->getCategoryId(),
            'message_type' => $this->getMessageType(),
            'was_read' => $this->wasRead(),
            'message_lifetime' => $this->getMessageLifetime(),
            'archived_at' => now()->toISOString(),
        ];
    }
}
