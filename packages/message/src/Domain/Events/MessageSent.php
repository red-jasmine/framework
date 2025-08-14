<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\Message;

/**
 * 消息发送事件
 */
class MessageSent
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
        return 'message.sent';
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
     * 获取推送状态
     */
    public function getPushStatus(): string
    {
        return $this->message->push_status->value;
    }

    /**
     * 是否推送成功
     */
    public function isPushSuccessful(): bool
    {
        return $this->message->push_status->value === 'sent';
    }

    /**
     * 获取推送渠道
     */
    public function getPushChannels(): array
    {
        return $this->message->channels ?? [];
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
            'title' => $this->message->title,
            'push_status' => $this->getPushStatus(),
            'channels' => $this->getPushChannels(),
            'sent_at' => now()->toISOString(),
        ];
    }
}
