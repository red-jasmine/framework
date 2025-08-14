<?php

declare(strict_types=1);

namespace RedJasmine\Message\Domain\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use RedJasmine\Message\Domain\Models\Enums\PushChannelEnum;
use RedJasmine\Message\Domain\Models\Message;
use RedJasmine\Message\Domain\Models\MessagePushLog;
use RedJasmine\Message\Domain\Models\ValueObjects\PushResult;

/**
 * 消息推送事件
 */
class MessagePushed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Message $message,
        public readonly PushChannelEnum $channel,
        public readonly PushResult $result,
        public readonly ?MessagePushLog $pushLog = null
    ) {
    }

    /**
     * 获取事件标识
     */
    public function getEventId(): string
    {
        return 'message.pushed';
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
     * 获取推送渠道
     */
    public function getChannel(): string
    {
        return $this->channel->value;
    }

    /**
     * 获取推送状态
     */
    public function getPushStatus(): string
    {
        return $this->result->status->value;
    }

    /**
     * 是否推送成功
     */
    public function isSuccess(): bool
    {
        return $this->result->isSuccess();
    }

    /**
     * 是否推送失败
     */
    public function isFailed(): bool
    {
        return $this->result->isFailed();
    }

    /**
     * 获取响应时间
     */
    public function getResponseTime(): int
    {
        return $this->result->responseTime;
    }

    /**
     * 获取错误信息
     */
    public function getErrorMessage(): ?string
    {
        return $this->result->getErrorMessage();
    }

    /**
     * 获取外部ID
     */
    public function getExternalId(): ?string
    {
        return $this->result->externalId;
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
            'channel' => $this->getChannel(),
            'status' => $this->getPushStatus(),
            'success' => $this->isSuccess(),
            'response_time' => $this->getResponseTime(),
            'error_message' => $this->getErrorMessage(),
            'external_id' => $this->getExternalId(),
            'pushed_at' => $this->result->pushedAt->toISOString(),
        ];
    }
}
