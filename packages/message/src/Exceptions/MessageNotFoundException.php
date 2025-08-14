<?php

declare(strict_types=1);

namespace RedJasmine\Message\Exceptions;

/**
 * 消息未找到异常
 */
class MessageNotFoundException extends MessageException
{
    public function __construct(
        string $messageId,
        int $code = 404,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: "消息不存在: {$messageId}",
            errorCode: 'MESSAGE_NOT_FOUND',
            errorDetails: ['message_id' => $messageId],
            code: $code,
            previous: $previous
        );
    }

    /**
     * 创建消息ID异常
     */
    public static function withId(string $messageId): self
    {
        return new self($messageId);
    }

    /**
     * 创建多个消息ID异常
     */
    public static function withIds(array $messageIds): self
    {
        $exception = new self(implode(', ', $messageIds));
        $exception->addErrorDetail('message_ids', $messageIds);
        return $exception;
    }
}
